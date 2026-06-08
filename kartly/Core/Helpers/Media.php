<?php

use Carbon\CarbonPeriod;
use Core\Models\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

if (!function_exists('saveFileInStorage')) {
    /**
     * save file
     *
     * @param mixed $file
     * @param mixed $file_name
     * @param mixed $location
     * @return mixed
     */
    function saveFileInStorage($file, $static_path = null)
    {

        try {
            $upload_path = "uploaded";
            $disk = get_setting('file_storage');

            if ($static_path != null) {
                $destination_path = $upload_path . '/' . $static_path;
            } else {
                $dynamic_path = date("Y") . "/" . date("M");
                $destination_path = $upload_path . '/' . $dynamic_path;
            }

            $file_extension = $file->getClientOriginalExtension();
            $file_original_name = $file->getClientOriginalName();
            $file_file_size = $file->getSize();
            $exploded_file_original_name = explode('.', $file_original_name);
            $original_file_name_without_extension = $exploded_file_original_name[0];

            //Store file 

            if ($disk == 'amazons3') {
                $file_full_path = Storage::disk('s3')->put($destination_path, $file);
            } else {
                if (!File::exists('public/' . $destination_path)) {
                    File::makeDirectory('public/' . $destination_path, 0777, true);
                }
                $file_full_path = $file->store($destination_path, $disk);
                if (File::exists('public/' . $file_full_path)) {
                    chmod('public/' . $file_full_path, 0777);
                }
            }

            $file_type = '';

            if ($file_extension == 'pdf') {
                $file_type = 'pdf';
            } elseif ($file_extension == 'zip') {
                $file_type = 'zip';
            } elseif ($file_extension == 'mp4') {
                $file_type = 'video';
            } elseif ($file_extension == 'webp') {
                $file_type = 'webp';
            } else {
                $file_type = 'image';
            }

            //Store file info in database
            $uploaded_file = new UploadedFile();
            $uploaded_file->name = $original_file_name_without_extension;
            $uploaded_file->title = $original_file_name_without_extension;
            $uploaded_file->alt = $original_file_name_without_extension;
            $uploaded_file->caption = $original_file_name_without_extension;
            $uploaded_file->description = $original_file_name_without_extension;
            $uploaded_file->size = $file_file_size;
            $uploaded_file->path = $file_full_path;
            $uploaded_file->disk = $disk;
            $uploaded_file->folder_name = $destination_path;
            $uploaded_file->file_type = $file_extension;
            $uploaded_file->uploaded_by = Auth::user() != null ? Auth::user()->name : null;
            $uploaded_file->user_id = Auth::user() != null ? Auth::user()->id : null;
            $uploaded_file->extension = $file_extension;
            $uploaded_file->saveOrFail();

            //customize images with Intervention image 
            if ($file_type == 'image') {
                $resizes_formats = customizeImage($file_full_path, $destination_path, $disk, $file);
                $uploaded_file->variant = json_encode($resizes_formats);
                $uploaded_file->update();
            }

            return $uploaded_file->id;
        } catch (\Exception $e) {
            return null;
        }
    }
}


if (!function_exists('customizeImage')) {
    /**
     * Will customize image
     *
     * @return mixed
     */
    function customizeImage($file_full_path, $destination_path, $disk, $file)
    {
        try {

            if ($disk == 'amazons3') {
                $image_source_path = Storage::disk('s3')->get($file_full_path);
            } else {
                $image_source_path = 'public/' . $file_full_path;
            }
            $full_path_array = explode('/', $file_full_path);
            $file_full_name = $full_path_array[sizeof($full_path_array) - 1];
            $file_full_name_array = explode('.', $file_full_name);
            $file_name = $file_full_name_array[0];
            $extension = $file_full_name_array[1];

            if ($disk == 'amazons3') {
                $modified_file_path_prefix = $destination_path . '/' . $file_name;
            } else {
                $modified_file_path_prefix = 'public/' . $destination_path . '/' . $file_name;
            }


            //Apply Water mark
            $is_watermark_enable = get_setting('watermark_status');
            if ($is_watermark_enable == 'on') {
                $original_path = $modified_file_path_prefix . '.' . $extension;
                applyWaterMarkImage($image_source_path, $original_path, $disk);
            }

            //Cropping theme based image
            $active_theme = getActiveTheme();
            if ($active_theme != null) {
                $theme_path = $active_theme->location;
                $cropping_sizes = config($theme_path . '.image_cropping_sizes');
                if ($cropping_sizes != null) {
                    foreach ($cropping_sizes as $size) {
                        $image_dimension = explode('x', $size);
                        $resizing_file_path = $modified_file_path_prefix . $size . '.' . $extension;

                        if ($disk == 'amazons3') {
                            $cropping_img = Image::make($file)
                                ->resize($image_dimension[0], $image_dimension[1])
                                ->crop($image_dimension[0], $image_dimension[1]);
                            Storage::disk('s3')->put($resizing_file_path, $cropping_img->stream()->__toString());
                        } else {
                            $cropping_img = Image::make($image_source_path)
                                ->resize($image_dimension[0], $image_dimension[1])
                                ->crop($image_dimension[0], $image_dimension[1]);
                            $cropping_img->save($resizing_file_path);
                            if (File::exists($resizing_file_path)) {
                                chmod($resizing_file_path, 0777);
                            }
                        }
                    }
                    return $cropping_sizes;
                }
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }
}

if (!function_exists('applyWaterMarkImage')) {
    /**
     * Apply Water mark
     *
     * @return mixed
     */
    function applyWaterMarkImage($image_source_path, $original_path, $disk)
    {
        $watermark_image_opacity = get_setting('water_marking_image_opacity') != null ? get_setting('water_marking_image_opacity') : 1;
        $watermark_image_position = get_setting('watermark_image_position') != null ? get_setting('watermark_image_position') : 'center';
        $watermark_image_id = get_setting('watermark_image');
        $watermark_image = get_setting('watermark_image') != null ? getFilePath($watermark_image_id, false) : null;

        if ($watermark_image != null) {
            $water_mark = Image::make(trim($watermark_image, '/'));
            $water_mark->opacity((int)$watermark_image_opacity);
            $modified_image = Image::make($image_source_path);
            $modified_image->insert($water_mark, $watermark_image_position, 10, 10);
            if ($disk == 'amazons3') {
                Storage::disk('s3')->put($original_path, $modified_image->stream()->__toString());
            } else {
                $modified_image->save($image_source_path);
            }
        }

        return true;
    }
}

if (!function_exists('removeMediaById')) {
    /**
     * Will remove single media by id
     *
     * @param Int $media_id
     * @return bool
     */
    function removeMediaById($media_id)
    {
        try {
            DB::beginTransaction();
            $media = UploadedFile::find($media_id);

            $path = "";
            if ($media != null) {
                $path = 'public/' . $media->path;
            }

            if ($media != null && $media->variant != null && $media->variant != 'null') {
                $all_variants = json_decode($media->variant);
                //Unlink variant images
                foreach ($all_variants as $variant_size) {
                    $variant_size_string = null;
                    if (is_array($variant_size)) {
                        $variant_size_string = implode('x', $variant_size);
                    } else {
                        $variant_size_string = $variant_size;
                    }

                    if ($variant_size_string != null) {
                        $full_path_array = explode('/', $path);
                        $file_full_name = $full_path_array[sizeof($full_path_array) - 1];
                        $file_full_name_array = explode('.', $file_full_name);
                        $file_name = $file_full_name_array[0];
                        $extension = $file_full_name_array[1];

                        $variant_image_name = $file_name . $variant_size_string . '.' . $extension;
                        if ($media->disk == 'amazons3') {
                            $file_path = $media->folder_name . '/' . $variant_image_name;
                            Storage::disk('s3')->delete($file_path);
                        } else {
                            $variant_image_path = 'public/' . $media->folder_name . '/' . $variant_image_name;
                            if (file_exists($variant_image_path)) {
                                unlink($variant_image_path);
                            }
                        }
                    }
                }
            }

            if ($media != null && $media->disk == 'amazons3') {
                $file_path = $media->path;
                Storage::disk('s3')->delete($file_path);
            } else {
                if (file_exists($path)) {
                    unlink($path);
                }
            }

            $media->delete();
            DB::commit();
            return true;
        } catch (Exception $ex) {
            DB::rollBack();
            return false;
        } catch (Error $ex) {
            DB::rollBack();
            return false;
        }
    }
}

if (!function_exists('getMonthsForUploadedFiles')) {
    /**
     * will return year-month list to filter uploaded files
     *
     * @return mixed
     */

    function getMonthsForUploadedFiles()
    {
        $starting_date = DB::table('tl_uploaded_files')
            ->select([
                'created_at as date'
            ])->first();

        $ending_date = DB::table('tl_uploaded_files')
            ->orderBy('id', 'desc')
            ->select([
                'created_at as date'
            ])->first();


        $data = [];

        if ($starting_date != null && $ending_date != null) {
            $result = CarbonPeriod::create($starting_date->date, '1 month', $ending_date->date);
            foreach ($result as $dt) {
                $data[$dt->format("m-Y")] = $dt->format("F-Y");
            }

            $todayDate = Carbon::now();
            $data[$todayDate->format("m-Y")] = $todayDate->format("F-Y");
        }
        return $data;
    }
}

if (!function_exists('getFileDetails')) {
    /**
     * return uploaded file details
     *
     * @param Int $id
     * @return Collection
     */

    function getFileDetails($id)
    {
        return UploadedFile::find($id);
    }
}

if (!function_exists('getFilePath')) {
    /**
     * return uploaded file path
     *
     * @param bool $placeholder
     * @param Int $id
     * @return String
     */

    function getFilePath($id, $placeholder = true, $size = null)
    {
        $file_info = Cache::rememberForever('file-path' . $id, function () use ($id) {
            return UploadedFile::select('path', 'disk')->find($id);
        });

        if (!$placeholder) {
            if ($file_info != null && $file_info->disk == "amazons3") {
                $file_path = $file_info != null ? env('AWS_URL') . '/' . $file_info->path : null;
            } else {
                $file_path = $file_info != null ? localMediaStoragePath() . '/' . $file_info->path : null;
            }
        }

        if ($placeholder) {
            if ($file_info != null && $file_info->disk == "amazons3") {
                $file_path = $file_info != null ? env('AWS_URL') . '/' . $file_info->path : getPlaceHolderImagePath();
            } else {
                $file_path = $file_info != null ? localMediaStoragePath() . '/' . $file_info->path : getPlaceHolderImagePath();
            }
        }

        if ($size != null) {
            $full_path_array = explode('/', $file_path);
            $file_full_name = $full_path_array[sizeof($full_path_array) - 1];
            $file_full_name_array = explode('.', $file_full_name);
            $file_name = sizeof($file_full_name_array) > 1 ? $file_full_name_array[0] : null;
            $extension = sizeof($file_full_name_array) > 1 ? $file_full_name_array[1] : null;
            $variant_image_name = $file_name . $size . '.' . $extension;
            array_pop($full_path_array);
            $location = implode('/', $full_path_array);
            $variant_image_path = $location . '/' . $variant_image_name;

            if ($file_info != null && $file_info->disk == "amazons3") {
                return $variant_image_path;
            } else {
                if (file_exists(trim($variant_image_path, '/'))) {
                    return $variant_image_path;
                }
            }
        }

        return $file_path;
    }
}

if (!function_exists('getPlaceHolderImagePath')) {


    function getPlaceHolderImagePath()
    {

        $image_id = get_setting('placeholder_image');

        if ($image_id != null) {
            return getFilePath($image_id, false);
        }

        return '/';
    }
}


if (!function_exists('getPlaceHolderImage')) {
    /**
     * will return placeholder image
     *
     * @return mixed
     */

    function getPlaceHolderImage()
    {

        $default = new StdClass;
        $default->placeholder_image = null;
        $default->placeholder_image_alt = null;

        $data = DB::table('tl_uploaded_files')
            ->where('id', '=', get_setting('placeholder_image'))
            ->select(['tl_uploaded_files.path as placeholder_image', 'tl_uploaded_files.alt as placeholder_image_alt'])
            ->first();

        if ($data == null) {
            return $default;
        }

        return $data;
    }
}


if (!function_exists('localMediaStoragePath')) {
    /**
     * return file storage base url
     *
     * 
     * @return String
     */

    function localMediaStoragePath()
    {
        return '/public';
    }
}

if (!function_exists('project_asset')) {
    /**
     * Generate an asset path for the application.
     *
     * @param  string  $path
     * @param  bool|null  $secure
     * @return string
     */
    function project_asset($path, $secure = null)
    {
        return app('url')->asset("/public/" . $path, $secure);
    }
}
