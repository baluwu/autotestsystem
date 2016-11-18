<header class="page-header">
    <nav class="navbar mega-menu" role="navigation">
        <div class="container-fluid">
            <div class="clearfix navbar-fixed-top">
                <button type="button" class="navbar-toggle" data-toggle="collapse"
                        data-target=".navbar-responsive-collapse">
                    <span class="sr-only">Toggle navigation</span>
                                <span class="toggle-icon">
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </span>
                </button>
                <a id="index" class="page-logo" href="/Index"> <img src="/Public/assets/pages/img/logo-big.png" alt="Rokid"> </a>

                <div class="topbar-actions">
                    <div class="btn-group-red btn-group">
                        <button type="button" class="btn btn-sm md-skip dropdown-toggle" data-toggle="dropdown"
                                data-hover="dropdown" data-close-others="true">
                            <i class="fa fa-plus"></i>
                        </button>
                        <ul class="dropdown-menu-v2" role="menu">
                            <li> <a href="/Single/add">添加用例</a> </li>
                            <li> <a href="/Group/add">添加用例组</a> </li>
                            <li> <a href="/Group/add">添加任务</a> </li>
                        </ul>
                    </div>
                    <div class="btn-group-img btn-group">
                        <button onclick="location.href='/Profile'" type="button"
                                class="btn btn-sm md-skip dropdown-toggle" data-toggle="dropdown"
                                data-hover="dropdown" data-close-others="true">
                            <span>Hi, {$admin_info.nickname}</span>
                            <img src="{$admin_info.headImg}" alt="{$admin_info.nickname}">
                        </button>
                        <ul class="dropdown-menu-v2" role="menu">
                            <li> <a href="/Profile"> <i class="fa fa-user"></i> 我的资料 </a> </li>
                            <li> <a href="/Logs/"> <i class="fa fa-reorder"></i> 我的日志 </a> </li>
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
                    <if condition="($CONTROLLER_NAME eq 'Index' OR $CONTROLLER_NAME eq '')"> class="active open
                        selected"
                    </if>
                    >
                    <a href="/Index/" class="text-uppercase">
                        <i class="fa fa-home"></i> Dashboard </a>
                    </li>
                  <li
                  <if condition="(CONTROLLER_NAME eq 'Group')">
                    <in name="ACTION_NAME" value="index,add,edit,execute_history,execute_history_show,execute_history_diff,recycle,single,single_add,single_edit,single_recycle">
                      class="active open selected"
                    </in>
                  </if>
                  >
                    <a href="/Group/index" class="text-uppercase">
                        <i class="fa fa-object-group"></i> 用例组管理 </a>
                    </li>
                    <li
                  <if condition="(CONTROLLER_NAME eq 'Task')">
                    <in name="ACTION_NAME" value="index">
                      class="active open selected"
                    </in>
                  </if>
                  >
                    <a href="/Task/index" class="text-uppercase"><i class="fa fa-tasks"></i> {:_e('Jobs')}</a>
                    </li>
                    <if condition="($admin_info.group_id eq 1)">

                        <li
                        <if condition="($CONTROLLER_NAME eq 'Manage')"> class="active open selected"</if>
                        >
                        <a href="/Manage/index" class="text-uppercase"> <i class="fa fa-users"></i> {:_e('User List')} </a>
                        </li>
                    </if>
                </ul>
            </div>
            <!-- END HEADER MENU -->
        </div>
        <!--/container-->
    </nav>
</header>
