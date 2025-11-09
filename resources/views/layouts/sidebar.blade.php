<header class="main-nav">
    <div class="sidebar-user text-center">
        <a class="setting-primary" href="javascript:void(0)"><i data-feather="settings"></i></a>
        <img class="img-90 rounded-circle" src="{{ asset('assets') }}/images/dashboard/1.png" alt="">
        <div class="badge-bottom"><span class="badge badge-primary">{{ Auth::user()->relRole->nama }}</span></div>
        <a href="javascript:void(0)">
            <h6 class="mt-3 f-14 f-w-600">{{ Auth::user()->nama }}</h6>
        </a>
        <p class="mb-0 font-roboto">Smart Pos</p>
    </div>
    <nav>
        <div class="main-navbar">
            <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
            <div id="mainnav">
                <ul class="nav-menu custom-scrollbar">
                    <li class="back-btn">
                        <div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2"
                                aria-hidden="true"></i></div>
                    </li>

                    <li class="dropdown"><a class="nav-link menu-title link-nav"
                            href="{{ route('dashboard.' . Auth::user()->role_name) }}"><i
                                data-feather="home"></i><span>Dashboard</span></a></li>

                    @php
                        use App\Models\MappingMenu;

                        $mappingMenus = MappingMenu::with('relMenu')
                            ->where('id_role', Auth::user()->id_role)
                            ->get();

                        $userMenus = $mappingMenus
                            ->map(function ($mapping) {
                                return $mapping->relMenu;
                            })
                            ->filter()
                            ->sortBy('urutan');

                        $menuTree = [];
                        foreach ($userMenus as $menu) {
                            if ($menu->id_parent == null) {
                                $menuTree[$menu->id] = [
                                    'menu' => $menu,
                                    'children' => [],
                                ];
                            }
                        }

                        foreach ($userMenus as $menu) {
                            if ($menu->id_parent != null && isset($menuTree[$menu->id_parent])) {
                                $menuTree[$menu->id_parent]['children'][] = $menu;
                            }
                        }
                    @endphp

                    @foreach ($menuTree as $item)
                        @php
                            $menu = $item['menu'];
                            $children = $item['children'];
                            $hasChildren = count($children) > 0;
                        @endphp

                        <li class="dropdown">
                            <a class="nav-link menu-title {{ !$hasChildren ? 'link-nav' : '' }}"
                                href="{{ $hasChildren ? 'javascript:void(0)' : ($menu->route ? route($menu->route) : 'javascript:void(0)') }}">
                                @if ($menu->icon)
                                    <i class="{{ $menu->icon }}"></i>
                                @else
                                    <i data-feather="circle"></i>
                                @endif
                                <span>{{ $menu->nama }}</span>
                            </a>

                            @if ($hasChildren)
                                <ul class="nav-submenu menu-content">
                                    @foreach ($children as $child)
                                        <li>
                                            <a
                                                href="{{ $child['route'] ? route($child['route']) : 'javascript:void(0)' }}">
                                                @if ($child['icon'])
                                                    <i class="{{ $child['icon'] }}"></i>
                                                @else
                                                    <i data-feather="circle"></i>
                                                @endif
                                                <span>{{ $child['nama'] }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach

                </ul>
            </div>
            <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
        </div>
    </nav>
</header>
