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
                        <i class="nav-icon fas fa-list"></i> Aplicaciones
                    </a>

                    <ul class="nav-dropdown-items">
                        <li class="nav-item">
                            <a class="nav-link {{
                            active_class(Route::is('admin/aplicacions'))
                        }}" href="{{ route('admin.aplicacions') }}">
                              Nuevas
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
                        <i class="nav-icon fas fa-list"></i> Contractors
                    </a>

                    <ul class="nav-dropdown-items">
                        <li class="nav-item">
                            <a class="nav-link {{
                            active_class(Route::is('admin/contractors'))
                        }}" href="{{ route('admin.contractors.index') }}">
                              Contractors
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{
                            active_class(Route::is('admin/contractors*'))
                        }}" href="{{ route('admin.contractors.create') }}">
                                Create Contractor
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
                        <i class="nav-icon fas fa-list"></i> Company
                    </a>

                    <ul class="nav-dropdown-items">
                        <li class="nav-item">
                            <a class="nav-link {{
                            active_class(Route::is('admin/company'))
                        }}" href="{{ route('admin.company.index') }}">
                              All Companies
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{
                            active_class(Route::is('admin/company*'))
                        }}" href="{{ route('admin.company.create') }}">
                                Create Company
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{
                        active_class(Route::is('admin/category'))
                    }}" href="{{ route('admin.category.index') }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <!-- @lang('menus.backend.sidebar.dashboard') -->
                        Category
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{
                        active_class(Route::is('admin/services'))
                    }}" href="{{ route('admin.services.index') }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <!-- @lang('menus.backend.sidebar.dashboard') -->
                        Services
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{
                        active_class(Route::is('admin/subservices'))
                    }}" href="{{ route('admin.subservices.index') }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <!-- @lang('menus.backend.sidebar.dashboard') -->
                        Sub-Services
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{
                        active_class(Route::is('admin/childsubservices'))
                    }}" href="{{ route('admin.childsubservices.index') }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <!-- @lang('menus.backend.sidebar.dashboard') -->
                        Child-Sub-Services
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{
                        active_class(Route::is('admin/questions'))
                    }}" href="{{ route('admin.questions.index') }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <!-- @lang('menus.backend.sidebar.dashboard') -->
                        Questions
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{
                        active_class(Route::is('admin/provinces'))
                    }}" href="{{ route('admin.provinces.index') }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        Provinces
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{
                        active_class(Route::is('admin/cities'))
                    }}" href="{{ route('admin.cities.index') }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <!-- @lang('menus.backend.sidebar.dashboard') -->
                        Cities
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{
                    active_class(Route::is('admin/payment'))
                }}" href="{{ route('admin.payment') }}">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                   Payment Management
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{
                    active_class(Route::is('admin/customer/payment'))
                }}" href="{{ route('admin.customer.payment') }}">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                   Customer Payment Management
                    </a>
                </li>


                <li class="nav-item">
                    <a class="nav-link {{
                        active_class(Route::is('admin/sitesetting'))
                    }}" href="{{ route('admin.site_setting.index') }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <!-- @lang('menus.backend.sidebar.dashboard') -->
                        Site Setting
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
                        Price Range
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{
                        active_class(Route::is('admin/contactus'))
                    }}" href="{{ route('admin.contactus.index') }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <!-- @lang('menus.backend.sidebar.dashboard') -->
                        Contact Us
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
                        Service Requests
                    </a>
                </li>
                 <li class="nav-item">
                    <a class="nav-link {{
                        active_class(Route::is('admin/review'))
                    }}" href="{{ route('admin.review.index') }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        Review
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{
                        active_class(Route::is('admin/newsletter'))
                    }}" href="{{ route('admin.newsletter.index') }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        Newsletter
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{
                        active_class(Route::is('admin/faqs'))
                    }}" href="{{ route('admin.faqs') }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        FAQ
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{
                        active_class(Route::is('admin/refund'))
                    }}" href="{{ route('admin.refund') }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        Refund Request
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{
                        active_class(Route::is('admin/package'))
                    }}" href="{{ route('admin.package.index') }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        Package
                    </a>
                </li>

                  <li class="nav-item nav-dropdown {{
                    active_class(Route::is('admin/company*'), 'open')
                }}">
                    <a class="nav-link nav-dropdown-toggle {{
                            active_class(Route::is('admin/company*'))
                        }}" href="#">
                        <i class="nav-icon fas fa-list"></i> Other
                    </a>

                    <ul class="nav-dropdown-items">
                        <li class="nav-item">
                            <a class="nav-link {{
                            active_class(Route::is('admin/work'))
                        }}" href="{{ route('admin.work.index') }}">
                              How Does It Work
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{
                            active_class(Route::is('admin/about_us'))
                        }}" href="{{ route('admin.about_us.index') }}">
                              About Us
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
                              Terms And Condition
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{
                            active_class(Route::is('admin/work_with_us'))
                        }}" href="{{ route('admin.work_with_us.index') }}">
                              Work With Us
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
