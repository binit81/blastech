<script src="{{URL::to('/')}}/public/template/jquery/dist/jquery.min.js"></script>
<?php
        $company_profile = 1;

        if(!sizeof($nav_type) == 0 && $nav_type[0]['company_name'] == '')
            {
                $company_profile = 0;
            }
    if(sizeof($nav_type)==0 || $company_profile == 0)
    {
     ?>
        <!-- HK Wrapper -->
        <div class="hk-wrapper hk-nav-toggle">
            <nav class="navbar navbar-expand-xl navbar-light fixed-top hk-navbar hk-navbar-alt">
            <a class="navbar-toggle-btn nav-link-hover navbar-toggler" href="javascript:void(0);" data-toggle="collapse" data-target="#navbarCollapseAlt" aria-controls="navbarCollapseAlt" aria-expanded="false" aria-label="Toggle navigation"><span class="feather-icon"><i data-feather="menu"></i></span></a>
            <a class="navbar-brand" href="{{URL::to('dashboard')}}">
                <img class="brand-img d-inline-block align-top" src="{{URL::to('/')}}/public/images/rc-logo-white-bg.PNG" width="100" alt="brand" />
            </a>
            <div class="collapse navbar-collapse" id="navbarCollapseAlt">
                <ul class="navbar-nav">
                    <?php
                    $current_urlx    =   url()->current();
                    $strArrayx       =   explode('/',$current_urlx);
                    $pageUrlx        =   end($strArrayx);
                    ?>

                    <li class="nav-item dropdown show-on-hover">
                        <a class="nav-link" href="{{URL::to('/')}}/company_profile" role="button" aria-haspopup="true" aria-expanded="false">Company Profile</a>
                    </li>
                </ul>
                
               
            </div>
            <ul class="navbar-nav hk-navbar-content">
                <li class="nav-item dropdown dropdown-authentication">
                    <a class="nav-link dropdown-toggle no-caret" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="media">
                            <div class="media-img-wrap">
                                <div class="avatar">
                                    <?php
                                    if($currentUser['employee_picture']=='')
                                    {
                                        ?>
                                        <img src="dist/img/user.png" alt="user" class="avatar-img rounded-circle">
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                        <img src="<?php echo EMPLOYEE_IMAGE_URL.$currentUser['employee_picture']?>" class="avatar-img rounded-circle">
                                        <?php
                                    }
                                    ?>
                                </div>
                                <span class="badge badge-success badge-indicator"></span>
                            </div>
                            <div class="media-body">
                                <span><?php echo $currentUser['employee_firstname'].' '.$currentUser['employee_middlename'].' '.$currentUser['employee_lastname']?><i class="zmdi zmdi-chevron-down"></i></span>
                            </div>
                        </div>
                    </a>
                    
                </li>
            </ul>

            <ul class="navbar-nav hk-navbar-content ml-20">
                <li><a href="{{URL::to('logout')}}"><i class="glyphicon glyphicon-off"></i>&nbsp;Logout</a></li>
            </ul>
        </nav>
        <!-- /Top Navbar -->


        <div id="hk_nav_backdrop" class="hk-nav-backdrop"></div>
        <!-- /Vertical Nav -->

        <!-- Main Content -->
        <div class="hk-pg-wrapper">
            <!-- Container -->

            <nav class="hk-breadcrumb" style="margin-bottom:-20px;" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-light bg-transparent">
                    <li class="breadcrumb-item"><a href="{{URL::to('dashboard')}}"><i class="ion ion-md-home"></i>&nbsp;Dashboard</a></li>
                    
                    <?php if(sizeof($urlData)!=0){?>
                    <?php if(sizeof($urlData['breadcrumb'])!=0){?>
                    <li class="breadcrumb-item" aria-current="page"><?php echo $urlData['breadcrumb'][0]['home_navigation']->nav_tab_display_name?></li>
                    <li class="breadcrumb-item" aria-current="page" style="font-weight:bold; font-size:15px;color:#008FB3;"><?php echo $urlData['breadcrumb'][0]['nav_tab_display_name']?><span id="PagecountResult"></span></li>
                    <?php }}?>
                </ol>
            </nav>

            
            @yield('main-hk-pg-wrapper')

        </div>
        </div>
        <?php
    }
    elseif($nav_type[0]['navigation_type']==2 && $company_profile == 1)
    {
        ?>
        <!-- HK Wrapper -->
        <div class="hk-wrapper hk-vertical-nav hk-nav-toggle">
        <nav class="navbar navbar-expand-xl navbar-light fixed-top hk-navbar hk-navbar-alt">
            <a class="navbar-toggle-btn nav-link-hover navbar-toggler" href="javascript:void(0);" data-toggle="collapse" data-target="#navbarCollapseAlt" aria-controls="navbarCollapseAlt" aria-expanded="false" aria-label="Toggle navigation"><span class="feather-icon"><i data-feather="menu"></i></span></a>
            <a class="navbar-brand" href="{{URL::to('dashboard')}}">
                <img class="brand-img d-inline-block align-top" src="{{URL::to('/')}}/public/images/rc-logo-white-bg.PNG" width="100" alt="brand" />
            </a>
            <div class="collapse navbar-collapse" id="navbarCollapseAlt">
                
                <form class="navbar-search-alt mt-15">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><span class="feather-icon"><i data-feather="search"></i></span></span>
                        </div>
                        <input class="form-control"  type="search" placeholder="Search" name="universalSearch" id="universalSearch" aria-label="Search">
                    </div>
                </form>
            </div>
            <ul class="navbar-nav hk-navbar-content">
                
                <li class="nav-item dropdown dropdown-notifications">
                    <a class="nav-link dropdown-toggle no-caret" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="feather-icon"><i data-feather="bell"></i></span><span class="badge-wrap"><span class="badge badge-primary badge-indicator badge-indicator-sm badge-pill pulse"></span></span></a>
                    <div class="dropdown-menu dropdown-menu-right" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                        <h6 class="dropdown-header">Notifications <a href="javascript:void(0);" class="">View all</a></h6>
                        <div class="notifications-nicescroll-bar">
                            <a href="javascript:void(0);" class="dropdown-item">
                                <div class="media">
                                    <div class="media-img-wrap">
                                        <div class="avatar avatar-sm">
                                            <img src="dist/img/avatar1.jpg" alt="user" class="avatar-img rounded-circle">
                                        </div>
                                    </div>
                                    <div class="media-body">
                                        <div>
                                            <div class="notifications-text"><span class="text-dark text-capitalize">Evie Ono</span> accepted your invitation to join the team</div>
                                            <div class="notifications-time">12m</div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            
                        </div>
                    </div>
                </li>
                <li class="nav-item dropdown dropdown-authentication">
                    <a class="nav-link dropdown-toggle no-caret" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="media">
                            <div class="media-img-wrap">
                                <div class="avatar">
                                    <?php
                                    if($currentUser['employee_picture']=='')
                                    {
                                        ?>
                                        <img src="dist/img/user.png" alt="user" class="avatar-img rounded-circle">
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                        <img src="<?php echo EMPLOYEE_IMAGE_URL.$currentUser['employee_picture']?>" class="avatar-img rounded-circle">
                                        <?php
                                    }
                                    ?>
                                </div>
                                <span class="badge badge-success badge-indicator"></span>
                            </div>
                            <div class="media-body">
                                <span><?php echo $currentUser['employee_firstname'].' '.$currentUser['employee_middlename'].' '.$currentUser['employee_lastname']?><i class="zmdi zmdi-chevron-down"></i></span>
                            </div>
                        </div>
                    </a>
                </li>
            </ul>

            <ul class="navbar-nav hk-navbar-content ml-20">
                <li><a href="{{URL::to('logout')}}"><i class="glyphicon glyphicon-off"></i>&nbsp;Logout</a></li>
            </ul>
        </nav>
        <!-- /Top Navbar -->

        <?php
        $current_urlx    =   url()->current();
        $strArrayx       =   explode('/',$current_urlx);
        $pageUrlx        =   end($strArrayx);
        ?>

        <!-- Vertical Nav -->
        <nav class="hk-nav hk-nav-dark">
            <a href="javascript:void(0);" id="hk_nav_close" class="hk-nav-close"><span class="feather-icon"><i data-feather="x"></i></span></a>
            <div class="nicescroll-bar">
                <div class="navbar-nav-wrap">

                    <ul class="navbar-nav flex-column">

                        <?php
                        if(session('ccompany_profile') ==0)
                        {

                        }
                        else
                        {

                        if($navLinks['chk_master']==1)
                        {
                            ?>
                            @foreach($navLinks['navLinks'] AS $k=>$v)

                            <?php

                            if($v['nav_label']=='' and $v['parent']==0){
                                ?>
                                <li class="nav-item <?php echo $pageUrlx==$v['nav_url']?'active':''?>">
                                <a class="nav-link" href="{{URL::to($v->nav_url)}}">
                                    <i class="{{URL::to($v->nav_icon_class)}}"></i>
                                    <label class="form-label">{{$v->nav_display_name}}</label></a>
                                </li>
                                <?php
                            }

                            if($v['nav_label']!=''){
                                ?>
                                <div class="nav-header">
                                <span><?php echo $v['nav_display_name']?></span>
                                <span><?php echo $v['nav_label']?></span>
                                </div>
                                <?php
                            }
                            foreach($v['home_navigations_data'] as $key=>$val)
                            {
                                ?>
                                <li class="nav-item <?php echo $pageUrlx==$val['nav_url']?'active':''?>">
                                <a class="nav-link" href="{{URL::to($val->nav_url)}}">
                                    <i class="{{URL::to($val->nav_icon_class)}}"></i>
                                    <label class="form-label">{{$val->nav_display_name}}</label></a>
                                </li>
                                <?php
                            }
                            
                            ?>
                            @endforeach
                            <?php
                        }   
                        else
                        { 
                        ?>

                            @foreach($navLinks['navLinks'] AS $k=>$v)

                            <?php
                                $MainLinks  =  $v['home_navigations']['nav_url']!=''?$v['home_navigations']['nav_url']:'javascript:void();'; 
                            ?>

                            <?php
                                if($v['home_navigations']['nav_label']=='')
                                {
                                    if(sizeof($urlData['breadcrumb'])!=0)
                                    {
                                        $check  =   $urlData['breadcrumb'][0]['home_navigation_id']==$v['home_navigations']['home_navigation_id']?'active':'';
                                    }
                                    else
                                    {
                                        $check  =   '';
                                        if($v['home_navigations']['home_navigation_id']=='1')
                                        {
                                           $check  =   'active'; 
                                        }
                                    }
                                    ?>
                                    <li class="nav-item <?php echo $check?>">
                                    <a class="nav-link" href="{{$MainLinks}}">
                                        <i class="{{$v['home_navigations']['nav_icon_class']}}"></i>
                                        <label class="form-label">{{$v['home_navigations']['nav_display_name']}}</label></a>
                                    </li>
                                    <?php
                                }
                                if($v['home_navigations']['nav_label']!='')
                                {
                            ?>
                                <div class="nav-header">
                                <span>{{$v['home_navigations']['nav_display_name']}}</span>
                                <span>{{$v['home_navigations']['nav_label']}}</span>
                                </div>


                            <?php
                                }
                            ?>
                                <!-- SUB LINKS START -->
                                @foreach($v['sub'] as $s=>$ssvalue)
                                    <li class="nav-item <?php echo $pageUrlx==$ssvalue['home_navigations_data_s']['nav_url']?'active':''?>">
                                    <a class="nav-link cursor" href="{{$ssvalue['home_navigations_data_s']['nav_url']}}">
                                        <i class="{{$ssvalue['home_navigations_data_s']['nav_icon_class']}}"></i>
                                        <label class="form-label">{{$ssvalue['home_navigations_data_s']['nav_display_name']}}</label></a>
                                    </li>
                                @endforeach
                                <!-- SUB LINKS END -->

                            @endforeach
                

                        <?php
                        } 
                    }

                        
                        ?>

                    </ul>

                    <hr class="nav-separator">


                </div>
            </div>
        </nav>
        <div id="hk_nav_backdrop" class="hk-nav-backdrop"></div>
        <!-- /Vertical Nav -->

        <!-- Main Content -->
        <div class="hk-pg-wrapper">
            <!-- Container -->

            <nav class="hk-breadcrumb" style="margin-bottom:-20px;" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-light bg-transparent">
                    <li class="breadcrumb-item"><a href="{{URL::to('dashboard')}}"><i class="ion ion-md-home"></i>&nbsp;Dashboard</a></li>
                    <?php if(sizeof($urlData)!=0){?>
                    <?php if(sizeof($urlData['breadcrumb'])!=0){?>
                    <li class="breadcrumb-item" aria-current="page"><?php echo $urlData['breadcrumb'][0]['home_navigation']->nav_tab_display_name?></li>
                    <li class="breadcrumb-item" aria-current="page" style="font-weight:bold; font-size:15px;color:#008FB3;"><?php echo $urlData['breadcrumb'][0]['nav_tab_display_name']?><span id="PagecountResult"></span></li>
                    <?php }}?>
                </ol>
            </nav>

            @yield('main-hk-pg-wrapper')

        </div>
        </div>

        <!-- /Main Content -->

    </div>
    <!-- /HK Wrapper -->
        <?php
    }
    elseif($nav_type[0]['navigation_type']==1 && $company_profile == 1)
    {
        ?>


        <!-- HK Wrapper -->
        <div class="hk-wrapper hk-nav-toggle">
            <nav class="navbar navbar-expand-xl navbar-light fixed-top hk-navbar hk-navbar-alt">
            <a class="navbar-toggle-btn nav-link-hover navbar-toggler" href="javascript:void(0);" data-toggle="collapse" data-target="#navbarCollapseAlt" aria-controls="navbarCollapseAlt" aria-expanded="false" aria-label="Toggle navigation"><span class="feather-icon"><i data-feather="menu"></i></span></a>
            <a class="navbar-brand" href="{{URL::to('dashboard')}}">
                <img class="brand-img d-inline-block align-top" src="{{URL::to('/')}}/public/images/rc-logo-white-bg.PNG" width="100" alt="brand" />
            </a>
            <div class="collapse navbar-collapse" id="navbarCollapseAlt">
                <ul class="navbar-nav">
                    <?php
                    $current_urlx    =   url()->current();
                    $strArrayx       =   explode('/',$current_urlx);
                    $pageUrlx        =   end($strArrayx);

                    // echo '<pre>'; print_r($navLinks); exit;
                    
                    if($navLinks['chk_master']==1)
                    {
                        ?>
                        @foreach($navLinks['navLinks'] AS $k=>$v)
                    
                        <?php if($v['nav_label']=='' and $v['parent']==0){ ?>
                        <li class="nav-item dropdown show-on-hover <?php echo $pageUrlx==$v['nav_url']?'active':''?>">
                            <a class="nav-link" href="{{URL::to('dashboard')}}" role="button" aria-haspopup="true" aria-expanded="false">{{$v->nav_display_name}}</a>
                        </li>
                        <?php } ?>

                        <?php if($v['nav_label']!=''){

                            if(sizeof($urlData['breadcrumb'])!=0)
                            {
                                if(sizeof($urlData)!=0){ 
                                    $check  =   $urlData['breadcrumb'][0]['home_navigation']->nav_tab_display_name==$v['nav_tab_display_name']?'active':'';
                                }
                                else
                                {
                                    $check  =   '';
                                }
                            }
                            else
                            {
                                $check  =   '';
                            }
                        ?>
                        <li class="nav-item dropdown show-on-hover <?php echo $check?>">
                            <a class="nav-link" href="javascript:void(0);" role="button" aria-haspopup="true" aria-expanded="false">{{$v->nav_display_name}}</a>

                            <?php if(sizeof($v['home_navigations_data'])!=0){?>
                             <div class="dropdown-menu" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                                <?php
                                foreach($v['home_navigations_data'] as $ke=>$val)
                                {
                                    ?><a class="dropdown-item <?php echo $pageUrlx==$val['nav_url']?'active':''?>" href="{{$val['nav_url']}}">{{$val['nav_display_name']}}</a><?php
                                }
                                ?>
                             </div>
                             <?php } ?>
                        </li>
                        <?php }?>

                        @endforeach
                        <?php
                    }
                    else
                    {

                        ?>
                        @foreach($navLinks['navLinks'] AS $k=>$v)

                        <?php

                        if(sizeof($urlData['breadcrumb'])!=0)
                        {
                            $check  =   $urlData['breadcrumb'][0]['home_navigation_id']==$v['home_navigations']['home_navigation_id']?'active':'';
                        }
                        else
                        {
                            $check  =   '';
                            if($v['home_navigations']['home_navigation_id']=='1')
                            {
                               $check  =   'active'; 
                            }
                        }
                        
                        $MainLinks  =  $v['home_navigations']['nav_url']!=''?$v['home_navigations']['nav_url']:'javascript:void();'; 
                        ?>

                        <li class="nav-item dropdown show-on-hover <?php echo $check?>">
                            <a class="nav-link" href="{{$MainLinks}}" role="button" aria-haspopup="true" aria-expanded="false">{{$v['home_navigations']['nav_display_name']}}</a>
                        
                        <?php if(sizeof($v['sub'])!=0){?>

                        <div class="dropdown-menu" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">

                            <!-- SUB LINKS START -->
                            @foreach($v['sub'] as $s=>$ssvalue)
                            <a class="dropdown-item <?php echo $pageUrlx==$ssvalue['home_navigations_data_s']['nav_url']?'active':''?>" href="{{$ssvalue['home_navigations_data_s']['nav_url']}}">{{$ssvalue['home_navigations_data_s']['nav_display_name']}}</a>
                            @endforeach
                            <!-- SUB LINKS END -->

                         </div>
                         
                         <?php }?>
                         </li>
                        
                        @endforeach
                    <?php
                    }
                    ?>
                    
                </ul>
                
                <form class="navbar-search-alt mt-15">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><span class="feather-icon"><i data-feather="search"></i></span></span>
                        </div>
                        <input class="form-control"  type="search" name="universalSearch" id="universalSearch" placeholder="Search" aria-label="Search">
                    </div>
                </form>
            </div>
            <ul class="navbar-nav hk-navbar-content">
                
                <li class="nav-item dropdown dropdown-notifications">
                    <a class="nav-link dropdown-toggle no-caret" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="feather-icon"><i data-feather="bell"></i></span><span class="badge-wrap"><span class="badge badge-primary badge-indicator badge-indicator-sm badge-pill pulse"></span></span></a>
                    <div class="dropdown-menu dropdown-menu-right" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                        <h6 class="dropdown-header">Notifications <a href="javascript:void(0);" class="">View all</a></h6>
                        <div class="notifications-nicescroll-bar">
                            <a href="javascript:void(0);" class="dropdown-item">
                                <div class="media">
                                    <div class="media-img-wrap">
                                        <div class="avatar avatar-sm">
                                            <img src="dist/img/avatar1.jpg" alt="user" class="avatar-img rounded-circle">
                                        </div>
                                    </div>
                                    <div class="media-body">
                                        <div>
                                            <div class="notifications-text"><span class="text-dark text-capitalize">Evie Ono</span> accepted your invitation to join the team</div>
                                            <div class="notifications-time">12m</div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            
                        </div>
                    </div>
                </li>
                <li class="nav-item dropdown dropdown-authentication">
                    <a class="nav-link dropdown-toggle no-caret" href="{{URL::to('my_profile')}}">
                        <div class="media">
                            <div class="media-img-wrap">
                                <div class="avatar">
                                    <?php
                                    if($currentUser['employee_picture']=='')
                                    {
                                        ?>
                                        <img src="dist/img/user.png" alt="user" class="avatar-img rounded-circle">
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                        <img src="<?php echo EMPLOYEE_IMAGE_URL.$currentUser['employee_picture']?>" class="avatar-img rounded-circle">
                                        <?php
                                    }
                                    ?>
                                </div>
                                <span class="badge badge-success badge-indicator"></span>
                            </div>
                            <div class="media-body">
                                
                                <span><?php echo $currentUser['employee_firstname'].' '.$currentUser['employee_middlename'].' '.$currentUser['employee_lastname']?><i class="zmdi zmdi-chevron-down"></i></span>

                            </div>
                        </div>
                    </a>
                    
                </li>
            </ul>

            <ul class="navbar-nav hk-navbar-content ml-20">
                <li><a href="{{URL::to('logout')}}"><i class="glyphicon glyphicon-off"></i>&nbsp;Logout</a></li>
            </ul>
        </nav>
        <!-- /Top Navbar -->


        <div id="hk_nav_backdrop" class="hk-nav-backdrop"></div>
        <!-- /Vertical Nav -->

        <!-- Main Content -->
        <div class="hk-pg-wrapper">
            <!-- Container -->

            <nav class="hk-breadcrumb" style="margin-bottom:-20px;" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-light bg-transparent">
                    <li class="breadcrumb-item"><a href="{{URL::to('dashboard')}}"><i class="ion ion-md-home"></i>&nbsp;Dashboard</a></li>
                    
                    <?php if(sizeof($urlData)!=0){?>
                    <?php if(sizeof($urlData['breadcrumb'])!=0){?>
                    <li class="breadcrumb-item" aria-current="page"><?php echo $urlData['breadcrumb'][0]['home_navigation']->nav_tab_display_name?></li>
                    <li class="breadcrumb-item" aria-current="page" style="font-weight:bold; font-size:15px;color:#008FB3;"><?php echo $urlData['breadcrumb'][0]['nav_tab_display_name']?><span class="PagecountResult"></span></li>
                    <?php }}?>
                </ol>
            </nav>

            @yield('main-hk-pg-wrapper')

        </div>
        </div>
        <?php
    }


?>

<script src="{{URL::to('/')}}/public/modulejs/home_navigation.js"></script>

<!-- Loader Start -->
<div class="loaderContainer" style="display:none;"><span><div class="loader"></div>Loading, Please wait...</div></span></div>
<!-- Loader End -->
