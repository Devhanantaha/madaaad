 <footer class="footer">
     <div class="d-flex footer-wraper justify-content-between pr-2 w-100">
         {!! xss_clean(get_setting('copyright_text')) !!}
         <p class="mb-0">Version {{ get_setting('system_version') }}</p>
     </div>
 </footer>
