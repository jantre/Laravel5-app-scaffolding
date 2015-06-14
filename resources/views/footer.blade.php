<footer class='footer'>
    <div class='container'>
        {!! HTML::script('js/jquery-1.11.1.min.js') !!}
        {!! HTML::script('js/main.js') !!}
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
        <script type='text/javascript'>
            $(document).ready(function(){
                $('.remembermespan').click(function(){
                    $('.remembermechk').click();
                });
                $('[data-toggle="offcanvas"]').click(function () {
                    $('.row-offcanvas').toggleClass('active')
                });
            });
        </script>
        <ul>
          <li><span class='link'>About</span></li>
          <li><span class='link'>Contact Us</span></li>
          <li><span class='link'>Help</span></li>
          <li><span class='link'>Privacy</span></li>
          <li><span class='link'>Terms</span></li>
        </ul>
 <!--
 IOS App
 Android App
 LinkedIn
 Facebook
 Twitter
-->
        <p>Made with <span class='ion-heart'></span></p>
    </div>
</footer>
