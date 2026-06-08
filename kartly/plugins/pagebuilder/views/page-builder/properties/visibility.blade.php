 <!--Visibility-->
 <div class="form-row mb-20">
     <div class="col-sm-12">
         <label class="font-14 bold black">{{ translate('Hide in Desktop') }} </label>
     </div>
     <div class="col-sm-12">
         <label class="switch glow primary medium">
             <input type="checkbox" name="hide_in_desktop" @checked(isset($properties['hide_in_desktop']))>
             <span class="control"></span>
         </label>
     </div>
 </div>
 <div class="form-row mb-20">
     <div class="col-sm-12">
         <label class="font-14 bold black">{{ translate('Hide in Tab') }} </label>
     </div>
     <div class="col-sm-12">
         <label class="switch glow primary medium">
             <input type="checkbox" name="hide_in_tab" @checked(isset($properties['hide_in_tab']))>
             <span class="control"></span>
         </label>
     </div>
 </div>
 <div class="form-row mb-20">
     <div class="col-sm-12">
         <label class="font-14 bold black">{{ translate('Hide in Mobile') }} </label>
     </div>
     <div class="col-sm-12">
         <label class="switch glow primary medium">
             <input type="checkbox" name="hide_in_mobile" @checked(isset($properties['hide_in_mobile']))>
             <span class="control"></span>
         </label>
     </div>
 </div>
 <!--End visibility-->
