<nav class="navbar navbar-default" role="navigation">
  <div class="container">
    <div class="navbar-header">
      <a class="navbar-brand hidden-xs" href="/"><span class='link'>Laravel Application</span></a>
        <button type="button" class="navbar-toggle navbar-toggle-left collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
    </div> <!-- navbar-header -->
        <div id="navbar" class="navbar-collapse collapse">
          <ul class='nav navbar-nav visible-xs hidden-sm'>
            <ul class='visible-xs'>
              @include('app.app_menu')
              <li class='border-bottom'></li>
            </ul>
            @include('app.navbar_menu')
          </ul>
          <div class="dropdown dropdown-menu-right hidden-xs">
            <div class="dropdown-toggle link hidden-xs" type="button" data-toggle="dropdown" aria-expanded="true">
              {{ Auth::user()->username }}
              <span class="caret"></span>
            </div>
            <ul class="dropdown-menu" role="menu">
              @include('app.navbar_menu')
            </ul>
          </div>
       </div><!--collapse-->
  </div><!-- container -->
</nav>
