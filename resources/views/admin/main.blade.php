<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>@yield('title') | Admin</title>
		<meta name="description" content="Latest updates and statistic charts">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
		<meta name="csrf-token" content="{{ csrf_token() }}" />
		<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
		<script>
			WebFont.load({
				google: {
					"families": ["Poppins:300,400,500,600,700", "Roboto:300,400,500,600,700"]
				},
				active: function() {
					sessionStorage.fonts = true;
				}
			});
		</script>
		<link href="/assets/vendors/base/vendors.bundle.css" rel="stylesheet" type="text/css" />
		<link href="/assets/demo/default/base/style.bundle.css" rel="stylesheet" type="text/css" />
		<link href="/css/loader.css" rel="stylesheet" type="text/css" />
        @yield('page_css')
		<link rel="shortcut icon" href="/assets/demo/default/media/img/logo/favicon.ico" />
		<script>
			(function(i, s, o, g, r, a, m) {
				i['GoogleAnalyticsObject'] = r;
				i[r] = i[r] || function() {
					(i[r].q = i[r].q || []).push(arguments)
				}, i[r].l = 1 * new Date();
				a = s.createElement(o),
					m = s.getElementsByTagName(o)[0];
				a.async = 1;
				a.src = g;
				m.parentNode.insertBefore(a, m)
			})(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');
			ga('create', 'UA-37564768-1', 'auto');
			ga('send', 'pageview');
		</script>
	</head>
	<body class="m-page--fluid m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--fixed m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default">
		<div class="loader-container">
			<div class="spinner">
				<div class="cube1"></div>
				<div class="cube2"></div>
			</div>
		</div>
		<div class="m-grid m-grid--hor m-grid--root m-page">
			<header id="m_header" class="m-grid__item    m-header " m-minimize-offset="200" m-minimize-mobile-offset="200">
				<div class="m-container m-container--fluid m-container--full-height">
					<div class="m-stack m-stack--ver m-stack--desktop">
						<div class="m-stack__item m-brand  m-brand--skin-dark ">
							<div class="m-stack m-stack--ver m-stack--general">
								<div class="m-stack__item m-stack__item--middle m-brand__logo">
									<a href="index.html" class="m-brand__logo-wrapper">
										<img alt="" src="/assets/images/logo.png" />
									</a>
								</div>
								<div class="m-stack__item m-stack__item--middle m-brand__tools">
									<a href="javascript:;" id="m_aside_left_minimize_toggle" class="m-brand__icon m-brand__toggler m-brand__toggler--left m--visible-desktop-inline-block  ">
										<span></span>
									</a>
									<a href="javascript:;" id="m_aside_left_offcanvas_toggle" class="m-brand__icon m-brand__toggler m-brand__toggler--left m--visible-tablet-and-mobile-inline-block">
										<span></span>
									</a>
									<a id="m_aside_header_topbar_mobile_toggle" href="javascript:;" class="m-brand__icon m--visible-tablet-and-mobile-inline-block">
										<i class="flaticon-more"></i>
									</a>
								</div>
							</div>
						</div>
						<div class="m-stack__item m-stack__item--fluid m-header-head" id="m_header_nav">
							<button class="m-aside-header-menu-mobile-close  m-aside-header-menu-mobile-close--skin-dark " id="m_aside_header_menu_mobile_close_btn">
								<i class="la la-close"></i>
							</button>
							<div id="m_header_topbar" class="m-topbar  m-stack m-stack--ver m-stack--general m-stack--fluid">
								<div class="m-stack__item m-topbar__nav-wrapper">
									<ul class="m-topbar__nav m-nav m-nav--inline">
										<li class="m-nav__item m-topbar__user-profile m-topbar__user-profile--img  m-dropdown m-dropdown--medium m-dropdown--arrow m-dropdown--header-bg-fill m-dropdown--align-right m-dropdown--mobile-full-width m-dropdown--skin-light" m-dropdown-toggle="click">
											<a href="#" class="m-nav__link m-dropdown__toggle">
												<span class="m-topbar__userpic">
													<img src="/assets/app/media/img/users/user4.jpg" class="m--img-rounded m--marginless" alt="" />
												</span>
												<span class="m-topbar__username m--hide">{{Auth::guard('admin')->user()->name}}</span>
											</a>
											<div class="m-dropdown__wrapper">
												<span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
												<div class="m-dropdown__inner">
													<div class="m-dropdown__header m--align-center" style="background: url(/assets/app/media/img/misc/user_profile_bg.jpg); background-size: cover;">
														<div class="m-card-user m-card-user--skin-dark">
															<div class="m-card-user__pic">
																<img src="/assets/app/media/img/users/user4.jpg" class="m--img-rounded m--marginless" alt="" />
															</div>
															<div class="m-card-user__details">
																<span class="m-card-user__name m--font-weight-500">{{Auth::guard('admin')->user()->name}}</span>
																<a href="" class="m-card-user__email m--font-weight-300 m-link">{{Auth::guard('admin')->user()->email}}</a>
															</div>
														</div>
													</div>
													<div class="m-dropdown__body">
														<div class="m-dropdown__content">
															<ul class="m-nav m-nav--skin-light">
																<li class="m-nav__section m--hide">
																	<span class="m-nav__section-text">Section</span>
																</li>
																<!-- <li class="m-nav__item">
																	<a href="#" class="m-nav__link">
																		<i class="m-nav__link-icon flaticon-profile-1"></i>
																		<span class="m-nav__link-title">
																			<span class="m-nav__link-wrap">
																				<span class="m-nav__link-text">My Profile</span>
																				<span class="m-nav__link-badge">
																					<span class="m-badge m-badge--success">2</span>
																				</span>
																			</span>
																		</span>
																	</a>
																</li>
																<li class="m-nav__item">
																	<a href="#" class="m-nav__link">
																		<i class="m-nav__link-icon flaticon-share"></i>
																		<span class="m-nav__link-text">Activity</span>
																	</a>
																</li>
																<li class="m-nav__item">
																	<a href="#" class="m-nav__link">
																		<i class="m-nav__link-icon flaticon-chat-1"></i>
																		<span class="m-nav__link-text">Messages</span>
																	</a>
																</li>
																<li class="m-nav__separator m-nav__separator--fit">
																</li>
																<li class="m-nav__item">
																	<a href="#" class="m-nav__link">
																		<i class="m-nav__link-icon flaticon-info"></i>
																		<span class="m-nav__link-text">FAQ</span>
																	</a>
																</li>
																<li class="m-nav__item">
																	<a href="#" class="m-nav__link">
																		<i class="m-nav__link-icon flaticon-lifebuoy"></i>
																		<span class="m-nav__link-text">Support</span>
																	</a>
																</li>
																<li class="m-nav__separator m-nav__separator--fit">
																</li> -->
																<li class="m-nav__item">
																	<a href="{{ route('admin.logout') }}" class="btn m-btn--pill btn-secondary m-btn m-btn--custom m-btn--label-brand m-btn--bolder" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
																	{{ __('menu.logout') }}
																	</a>
                                                                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                                                                        @csrf
                                                                    </form>
																</li>
															</ul>
														</div>
													</div>
												</div>
											</div>
										</li>
										<li id="m_quick_sidebar_toggle" class="m-nav__item">
											<a href="javascript:void(0);" class="m-nav__link m-dropdown__toggle">
												<span class="m-nav__link-icon">
													<i class="flaticon-grid-menu"></i>
												</span>
											</a>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</header>

			<div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">

				<button class="m-aside-left-close  m-aside-left-close--skin-dark " id="m_aside_left_close_btn">
					<i class="la la-close"></i>
				</button>
				<div id="m_aside_left" class="m-grid__item	m-aside-left  m-aside-left--skin-dark ">
					<div id="m_ver_menu" class="m-aside-menu  m-aside-menu--skin-dark m-aside-menu--submenu-skin-dark " m-menu-vertical="1" m-menu-scrollable="1" m-menu-dropdown-timeout="500" style="position: relative;">
						<ul class="m-menu__nav  m-menu__nav--dropdow n-submenu-arrow ">
							<li class="m-menu__item @if(strpos(Route::currentRouteName(), 'dashboard') !== false) m-menu__item--active @endif" aria-haspopup="true">
								<a href="{{route('admin.dashboard')}}" class="m-menu__link ">
									<i class="m-menu__link-icon flaticon-line-graph"></i>
									<span class="m-menu__link-title">
										<span class="m-menu__link-wrap">
											<span class="m-menu__link-text">{{ __('menu.main') }}</span>
										</span>
									</span>
								</a>
							</li>
                            <li class="m-menu__item @if(strpos(Route::currentRouteName(), 'users') !== false) m-menu__item--active @endif" aria-haspopup="true">
								<a href="{{route('admin.users')}}" class="m-menu__link ">
									<i class="m-menu__link-icon flaticon-users"></i>
									<span class="m-menu__link-title">
										<span class="m-menu__link-wrap">
											<span class="m-menu__link-text">{{ __('menu.users') }}</span>
										</span>
									</span>
								</a>
							</li>
							<li class="m-menu__item @if(strpos(Route::currentRouteName(), 'settings') !== false) m-menu__item--active @endif" aria-haspopup="true">
								<a href="{{route('admin.settings')}}" class="m-menu__link ">
									<i class="m-menu__link-icon flaticon-settings"></i>
									<span class="m-menu__link-title">
										<span class="m-menu__link-wrap">
											<span class="m-menu__link-text">{{ __('menu.settings') }}</span>
										</span>
									</span>
								</a>
							</li>
							<li class="m-menu__item @if(strpos(Route::currentRouteName(), 'cupons') !== false) m-menu__item--active @endif" aria-haspopup="true">
								<a href="{{route('admin.cupons')}}" class="m-menu__link ">
									<i class="m-menu__link-icon flaticon-medal"></i>
									<span class="m-menu__link-title">
										<span class="m-menu__link-wrap">
											<span class="m-menu__link-text">{{ __('menu.coupons') }}</span>
										</span>
									</span>
								</a>
							</li>
							<li class="m-menu__item  m-menu__item--submenu @if(strpos(Route::currentRouteName(), 'admin.categories') !== false  || strpos(Route::currentRouteName(), 'admin.products') !== false || strpos(Route::currentRouteName(), 'admin.extras') !== false) m-menu__item--open m-menu__item--expanded @endif" aria-haspopup="true" m-menu-submenu-toggle="hover">
								<a href="javascript:;" class="m-menu__link m-menu__toggle">
									<i class="m-menu__link-icon flaticon-interface-8"></i>
									<span class="m-menu__link-text">{{ __('menu.catalogus') }}</span>
									<i class="m-menu__ver-arrow la la-angle-right"></i>
								</a>
								<div class="m-menu__submenu ">
									<span class="m-menu__arrow"></span>
									<ul class="m-menu__subnav">
										<li class="m-menu__item @if(strpos(Route::currentRouteName(), 'admin.categories') !== false) m-menu__item--active @endif" aria-haspopup="true">
											<a href="{{route('admin.categories')}}" class="m-menu__link ">
												<i class="m-menu__link-bullet m-menu__link-bullet--dot">
													<span></span>
												</i>
												<span class="m-menu__link-text">{{ __('menu.categories') }}</span>
											</a>
										</li>
										<li class="m-menu__item @if(strpos(Route::currentRouteName(), 'admin.products') !== false) m-menu__item--active @endif" aria-haspopup="true">
											<a href="{{route('admin.products')}}" class="m-menu__link ">
												<i class="m-menu__link-bullet m-menu__link-bullet--dot">
													<span></span>
												</i>
												<span class="m-menu__link-text">{{ __('menu.products') }}</span>
											</a>
										</li>
										<li class="m-menu__item @if(strpos(Route::currentRouteName(), 'admin.extras') !== false) m-menu__item--active @endif" aria-haspopup="true">
											<a href="{{route('admin.extras')}}" class="m-menu__link ">
												<i class="m-menu__link-bullet m-menu__link-bullet--dot">
													<span></span>
												</i>
												<span class="m-menu__link-text">{{ __('menu.extras') }}</span>
											</a>
										</li>
									</ul>
								</div>
							</li>
							<li class="m-menu__item @if(strpos(Route::currentRouteName(), 'admin.push') !== false) m-menu__item--active @endif" aria-haspopup="true">
								<a href="{{route('admin.push')}}" class="m-menu__link ">
									<i class="m-menu__link-icon flaticon-alert"></i>
									<span class="m-menu__link-title">
										<span class="m-menu__link-wrap">
											<span class="m-menu__link-text">{{ __('menu.notifications') }}</span>
										</span>
									</span>
								</a>
							</li>
							<li class="m-menu__item @if(strpos(Route::currentRouteName(), 'admin.orders') !== false) m-menu__item--active @endif" aria-haspopup="true">
								<a href="{{route('admin.orders')}}" class="m-menu__link ">
									<i class="m-menu__link-icon flaticon-clipboard"></i>
									<span class="m-menu__link-title">
										<span class="m-menu__link-wrap">
											<span class="m-menu__link-text">{{ __('menu.orders') }}</span>
										</span>
									</span>
								</a>
							</li>
						</ul>
					</div>
				</div>

				<div class="m-grid__item m-grid__item--fluid m-wrapper">
					<div class="m-subheader ">
						<div class="d-flex align-items-center">
							<div class="mr-auto">
								<h3 class="m-subheader__title ">@yield('page_title')</h3>
							</div>
							@yield('page_btn')
						</div>
					</div>
					<div class="m-content">
                        @yield('content')
					</div>
				</div>
			</div>

		</div>

		<div id="m_scroll_top" class="m-scroll-top">
			<i class="la la-arrow-up"></i>
		</div>

		<script src="/assets/vendors/base/vendors.bundle.js" type="text/javascript"></script>
		<script src="/assets/demo/default/base/scripts.bundle.js" type="text/javascript"></script>
		<script type="text/javascript">var globalMainUrl = "{{route('globalUrl')}}";</script>
        @yield('page_js')
		<script>
	        window.onload = function () {
	            setTimeout(function () {
	                $('.loader-container').fadeOut('slow');
	            }, 100);
	        }
	    </script>
	</body>
</html>
