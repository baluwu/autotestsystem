<header class="page-header">
    <nav class="navbar mega-menu" role="navigation">
        <div class="container-fluid">
            <div class="clearfix navbar-fixed-top">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="toggle-icon">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </span>
                </button>
                <a id="index" class="page-logo" href="/Index"> <img src="/Public/assets/pages/img/logo-big.png" alt="Rokid"> </a>
                <div class="topbar-actions">
                    <!--
                    <div class="btn-group-red btn-group">
                        <button type="button" class="btn btn-sm md-skip dropdown-toggle" data-toggle="dropdown"
                                data-hover="dropdown" data-close-others="true">
                            <i class="fa fa-plus"></i>
                        </button>
                        <ul class="dropdown-menu-v2" role="menu">
                            <li> <a href="/Single/add"><?php _e('Add Case'); ?></a> </li>
                            <li> <a href="/Group/add"><?php _e('Add Case Group'); ?></a> </li>
                            <li> <a href="/Group/add"><?php _e('Add Job'); ?></a> </li>
                        </ul>
                    </div>
                    -->
                    <div class="btn-group-img btn-group">
                        <button type="button" class="btn btn-sm md-skip lang-toggle" >
                            <if condition="$Think.session.SET_LANG_CONF eq 'en_US'">
                                <span class="font-white">中文</span>
                            <else />
                                <span class="font-white">English</span>
                            </if>
                        </button>
                        <button onclick="location.href='/Profile'" type="button"
                                class="btn btn-sm md-skip dropdown-toggle" data-toggle="dropdown"
                                data-hover="dropdown" data-close-others="true">
                            <span>Hi, {$admin_info.nickname}</span>
                            <img src="{$admin_info.headImg}" alt="{$admin_info.nickname}">
                        </button>
                        <ul class="dropdown-menu-v2" role="menu">
                            <li> <a href="/Profile"> <i class="fa fa-user"></i> <?php _e('My Information'); ?> </a> </li>
                            <li> <a href="/Logs/"> <i class="fa fa-reorder"></i> <?php _e('My Note'); ?> </a> </li>
                            <li class="divider"></li>
                            <li> <a href="#"> <i class="fa fa-lock"></i> Lock Screen </a> </li> 
                            <li> <a href="{:U('Login/out')}"> <i class="fa fa-sign-out"></i> Log Out </a> </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="nav-collapse collapse navbar-collapse navbar-responsive-collapse">
                <ul class="nav navbar-nav">
                    <li
                    <if condition="($CONTROLLER_NAME eq 'Index' OR $CONTROLLER_NAME eq '')"> class="active open selected" </if> >
                    <a href="/Index/" class="text-uppercase">
                        <i class="fa fa-home"></i><?php _e('Dashboard'); ?> </a>
                    </li>
                  <li
                  <if condition="(CONTROLLER_NAME eq 'Group')">
                    <in name="ACTION_NAME" value="index,add,edit,execute_history,execute_history_show,execute_history_diff,recycle,single,single_add,single_edit,single_recycle">
                      class="active open selected"
                    </in>
                  </if>
                  >
                    <a href="/Group/index" class="text-uppercase">
                        <i class="fa fa-object-group"></i><?php _e('Case Groups'); ?> </a>
                    </li>
                    <li
                  <if condition="(CONTROLLER_NAME eq 'Task')">
                    <in name="ACTION_NAME" value="index,execute_history_show">
                      class="active open selected"
                    </in>
                  </if>
                  >
                    <a href="/Task/index" class="text-uppercase"><i class="fa fa-tasks"></i> <?php _e('Jobs'); ?></a>
                    </li>
                    <if condition="($admin_info.group_id eq 1)">

                        <li
                        <if condition="($CONTROLLER_NAME eq 'Manage')"> class="active open selected"</if>
                        >
                        <a href="/Manage/index" class="text-uppercase"> <i class="fa fa-users"></i> <?php _e('Users'); ?> </a>
                        </li>
                    </if>
                </ul>
            </div>
            <!-- END HEADER MENU -->
        </div>
        <!--/container-->
    </nav>
</header>
