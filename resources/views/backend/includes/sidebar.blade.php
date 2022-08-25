<div class="sidebar">
    <nav class="sidebar-nav">
        <ul class="nav">
            <li class="nav-title">
                @lang('menus.backend.sidebar.general')
            </li>
            <li class="nav-item">
                <a class="nav-link {{
                    active_class(Route::is('admin/dashboard'))
                }}" href="{{ route('admin.dashboard') }}">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    @lang('menus.backend.sidebar.dashboard')
                </a>
            </li>

            @if ($logged_in_user->isAdmin())
                <li class="nav-title">
                    @lang('menus.backend.sidebar.system')
                </li>

                <li class="nav-item nav-dropdown {{
                    active_class(Route::is('admin/auth*'), 'open')
                }}">
                    <a class="nav-link nav-dropdown-toggle {{
                        active_class(Route::is('admin/auth*'))
                    }}" href="#">
                        <i class="nav-icon far fa-user"></i>
                        @lang('menus.backend.access.title')

                        @if ($pending_approval > 0)
                            <span class="badge badge-danger">{{ $pending_approval }}</span>
                        @endif
                    </a>

                    <ul class="nav-dropdown-items">
                        <li class="nav-item">
                            <a class="nav-link {{
                                active_class(Route::is('admin/auth/user*'))
                            }}" href="{{ route('admin.auth.user.index') }}">
                                @lang('labels.backend.access.users.management')

                                @if ($pending_approval > 0)
                                    <span class="badge badge-danger">{{ $pending_approval }}</span>
                                @endif
                            </a>
                        </li>
                        {{-- <li class="nav-item">
                            <a class="nav-link {{
                                active_class(Route::is('admin/auth/role*'))
                            }}" href="{{ route('admin.auth.role.index') }}">
                                @lang('labels.backend.access.roles.management')
                            </a>
                        </li> --}}
                    </ul>
                </li>

                <li class="divider"></li>

                <!--<li class="nav-item nav-dropdown {{
                    active_class(Route::is('admin/log-viewer*'), 'open')
                }}">
                        <a class="nav-link nav-dropdown-toggle {{
                            active_class(Route::is('admin/log-viewer*'))
                        }}" href="#">
                        <i class="nav-icon fas fa-list"></i> @lang('menus.backend.log-viewer.main')
                    </a>
                    <ul class="nav-dropdown-items">
                        <li class="nav-item">
                            <a class="nav-link {{
                            active_class(Route::is('admin/log-viewer'))
                        }}" href="{{ route('log-viewer::dashboard') }}">
                                @lang('menus.backend.log-viewer.dashboard')
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{
                            active_class(Route::is('admin/log-viewer/logs*'))
                        }}" href="{{ route('log-viewer::logs.list') }}">
                                @lang('menus.backend.log-viewer.logs')
                            </a>
                        </li>
                    </ul>
                </li>
 -->            
                <li class="nav-item nav-dropdown {{
                    active_class(Route::is('admin/aplicacions*'), 'open')
                }}">
                    <a class="nav-link nav-dropdown-toggle {{
                            active_class(Route::is('admin/aplicacions*'))
                        }}" href="#">
                        <i class="nav-icon fas fa-list"></i> 
                        @lang('labels.backend.other.applications')
                    </a>

                    <ul class="nav-dropdown-items">
                        
                       
                        <li class="nav-item">
                            <a class="nav-link {{
                            active_class(Route::is('admin/aplicacions'))
                        }}" href="{{ route('admin.aplicacions') }}">
                              @lang('labels.backend.other.new1')
                            </a>
                        </li>
                        <li class="nav-item">
                             <a class="nav-link {{
                             active_class(Route::is('admin/aplicacions1'))
                         }}" href="{{ route('admin.aplicacions1.index') }}">
                               @lang('labels.backend.other.new')
                               </a>
                         </li>

                    </ul>
                        
                </li>
                <li class="nav-item nav-dropdown {{
                    active_class(Route::is('admin/contractors*'), 'open')
                }}">
                    <a class="nav-link nav-dropdown-toggle {{
                            active_class(Route::is('admin/contractors*'))
                        }}" href="#">
                        <i class="nav-icon fas fa-list"></i> @lang('labels.backend.contractor.management')
                    </a>

                    <ul class="nav-dropdown-items">

                        <!-- <li class="nav-item">
                            <a class="nav-link {{
                            active_class(Route::is('admin/aplicacions1'))
                        }}" href="{{ route('admin.aplicacions1.index') }}">
                            @lang('labels.backend.other.new')
                            </a>
                        </li> -->

                        <li class="nav-item">
                            <a class="nav-link {{
                            active_class(Route::is('admin/contractors'))
                        }}" href="{{ route('admin.contractors.index') }}">
                              @lang('labels.backend.contractor.management')
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{
                            active_class(Route::is('admin/contractors'))
                        }}" href="{{ route('admin.contractors1.index') }}">
                              @lang('labels.backend.contractor.management_n')
                            </a>
                        </li>
                         
                        <li class="nav-item">
                            <a class="nav-link {{
                            active_class(Route::is('admin/contractors*'))
                        }}" href="{{ route('admin.contractors.create') }}">
                                @lang('labels.backend.other.create_contractor')
                            </a>
                        </li>
                    </ul>
                </li>


                <li class="nav-item nav-dropdown {{
                    active_class(Route::is('admin/company*'), 'open')
                }}">
                    <a class="nav-link nav-dropdown-toggle {{
                            active_class(Route::is('admin/company*'))
                        }}" href="#">
                        <i class="nav-icon fas fa-list"></i> @lang('labels.backend.company.company')
                    </a>

                    <ul class="nav-dropdown-items">
                        <li class="nav-item">
                            <a class="nav-link {{
                            active_class(Route::is('admin/company'))
                        }}" href="{{ route('admin.company.index') }}">
                            @lang('labels.backend.company.company')
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{
                            active_class(Route::is('admin/company'))
                        }}" href="{{ route('admin.company1.index') }}">
                            @lang('labels.backend.company.company_n')
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{
                            active_class(Route::is('admin/company*'))
                        }}" href="{{ route('admin.company.create') }}">
                            @lang('labels.backend.company.create')
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{
                        active_class(Route::is('admin/category'))
                    }}" href="{{ route('admin.category.index') }}">
                        <!-- <i class="nav-icon fas fa-tachometer-alt"></i> -->
                        <i class="nav-icon fa-bars"></i>
                        <!-- @lang('menus.backend.sidebar.dashboard') -->
                        @lang('labels.backend.category.category')
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{
                        active_class(Route::is('admin/banner'))
                    }}" href="{{ route('admin.banner.index') }}">
                        <i class="nav-icon fa-bars"></i>
                        @lang('labels.backend.banner.banner')
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{
                        active_class(Route::is('admin/services'))
                    }}" href="{{ route('admin.services.index') }}">
                        <!-- <i class="nav-icon fas fa-tachometer-alt"></i> -->
                        <i class="nav-icon fa-bars"></i>
                        <!-- @lang('menus.backend.sidebar.dashboard') -->
                        @lang('labels.backend.services.services')
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{
                        active_class(Route::is('admin/subservices'))
                    }}" href="{{ route('admin.subservices.index') }}">
                        <!-- <i class="nav-icon fas fa-tachometer-alt"></i> -->
                        <i class="nav-icon fa-bars"></i>
                        <!-- @lang('menus.backend.sidebar.dashboard') -->
                        @lang('labels.backend.subservices.subservices')
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{
                        active_class(Route::is('admin/childsubservices'))
                    }}" href="{{ route('admin.childsubservices.index') }}">
                        <!-- <i class="nav-icon fas fa-tachometer-alt"></i> -->
                        <i class="nav-icon fa-bars"></i>
                        <!-- @lang('menus.backend.sidebar.dashboard') -->
                        @lang('labels.backend.childsubservices.childsubservices')
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{
                        active_class(Route::is('admin/questions'))
                    }}" href="{{ route('admin.questions.index') }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <!-- @lang('menus.backend.sidebar.dashboard') -->
                        @lang('labels.backend.questions.questions')
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{
                        active_class(Route::is('admin/provinces'))
                    }}" href="{{ route('admin.provinces.index') }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        @lang('labels.backend.provinces.provinces')
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{
                        active_class(Route::is('admin/cities'))
                    }}" href="{{ route('admin.cities.index') }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <!-- @lang('menus.backend.sidebar.dashboard') -->
                        <!-- Cities -->
                        @lang('labels.backend.cities.city')
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{
                    active_class(Route::is('admin/payment'))
                }}" href="{{ route('admin.payment') }}">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    @lang('labels.backend.payment_management.payment_management')
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{
                    active_class(Route::is('admin/customer/payment'))
                }}" href="{{ route('admin.customer.payment') }}">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    @lang('labels.backend.customerpaymant.management') 
                    </a>
                </li>


                <li class="nav-item">
                    <a class="nav-link {{
                        active_class(Route::is('admin/sitesetting'))
                    }}" href="{{ route('admin.site_setting.index') }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        @lang('labels.backend.sitesetting.management')
                    </a>
                </li>

                {{-- <li class="nav-item">
                    <a class="nav-link {{
                        active_class(Route::is('admin/zone'))
                    }}" href="{{ route('admin.zone.index') }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        Zone
                    </a>
                </li> --}}

                 {{-- <li class="nav-item">
                    <a class="nav-link {{
                        active_class(Route::is('admin/polygon'))
                    }}" href="{{ route('admin.polygon.index') }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                       Polygon
                    </a>
                </li> --}}


                <li class="nav-item">
                    <a class="nav-link {{
                        active_class(Route::is('admin/price_range'))
                    }}" href="{{ route('admin.price_range.index') }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <!-- @lang('menus.backend.sidebar.dashboard') -->
                        @lang('labels.backend.price_range.price_range')
                        
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{
                        active_class(Route::is('admin/contactus'))
                    }}" href="{{ route('admin.contactus.index') }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <!-- @lang('menus.backend.sidebar.dashboard') -->
                        @lang('labels.backend.contactus.management')
                    </a>
                </li>

                  <!-- <li class="nav-item">
                    <a class="nav-link {{
                        active_class(Route::is('admin/area'))
                    }}" href="{{ route('admin.area.index') }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                         @lang('menus.backend.sidebar.dashboard') 
                         Area Management
                    </a>
                </li> -->


                 <li class="nav-item">
                    <a class="nav-link {{
                        active_class(Route::is('admin/service_request'))
                    }}" href="{{ route('admin.service_request.index') }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        @lang('labels.backend.service_request.service_requests')
                    </a>
                </li>
                 <li class="nav-item">
                    <a class="nav-link {{
                        active_class(Route::is('admin/review'))
                    }}" href="{{ route('admin.review.index') }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        @lang('labels.backend.review.review')
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{
                        active_class(Route::is('admin/newsletter'))
                    }}" href="{{ route('admin.newsletter.index') }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        @lang('labels.backend.newsletter.management')
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{
                        active_class(Route::is('admin/faqs'))
                    }}" href="{{ route('admin.faqs') }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        @lang('labels.backend.faq.faq')
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{
                        active_class(Route::is('admin/refund'))
                    }}" href="{{ route('admin.refund') }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        @lang('labels.backend.refund.refund')
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{
                        active_class(Route::is('admin/package'))
                    }}" href="{{ route('admin.package.index') }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        @lang('labels.backend.package.package')
                    </a>
                </li>

                  <li class="nav-item nav-dropdown {{
                    active_class(Route::is('admin/company*'), 'open')
                }}">
                    <a class="nav-link nav-dropdown-toggle {{
                            active_class(Route::is('admin/company*'))
                        }}" href="#">
                        <i class="nav-icon fas fa-list"></i> 
                        @lang('labels.backend.other.other')
                    </a>

                    <ul class="nav-dropdown-items">
                        <li class="nav-item">
                            <a class="nav-link {{
                            active_class(Route::is('admin/work'))
                        }}" href="{{ route('admin.work.index') }}">
                              @lang('labels.backend.work.work')
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{
                            active_class(Route::is('admin/about_us'))
                        }}" href="{{ route('admin.about_us.index') }}">
                              @lang('labels.backend.about.about')
                            </a>
                        </li>
                       <!--  <li class="nav-item">
                            <a class="nav-link {{
                            active_class(Route::is('admin/news'))
                        }}" href="{{ route('admin.news.index') }}">
                              News
                            </a>
                        </li> -->

                        <li class="nav-item">
                            <a class="nav-link {{
                            active_class(Route::is('admin/terms_and_condition'))
                        }}" href="{{ route('admin.terms_and_condition.index') }}">

                              @lang('labels.backend.terms_and_condition.terms_and_condition') 
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{
                            active_class(Route::is('admin/work_with_us'))
                        }}" href="{{ route('admin.work_with_us.index') }}">
                              @lang('labels.backend.work_with_us.work_with_us') 
                            </a>
                        </li>

                       <!--  <li class="nav-item">
                            <a class="nav-link {{
                            active_class(Route::is('admin/security-policy'))
                        }}" href="{{ route('admin.security-policy.index') }}">
                              Review Payment Security Policies
                            </a>
                        </li> -->
                    </ul>
                </li>
            @endif
        </ul>
    </nav>

    <button class="sidebar-minimizer brand-minimizer" type="button"></button>
</div><!--sidebar-->