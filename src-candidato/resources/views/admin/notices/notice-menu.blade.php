<nav id="notice-menu" class="shadow-md bg-blue-900 w-full z-30 top-0 text-white py-1 lg:py-6">
    <div class="w-full container mx-auto flex flex-wrap items-center justify-between mt-0 px-2 py-2 lg:py-6">
      <div id="logo-header" class="pl-4 flex items-center">
        <a class="text-white no-underline hover:no-underline font-bold text-2xl lg:text-4xl" href="/manager">
          <img class="w-40" src="{{asset('img/logo_ifc.png')}}" alt="Instituto Federal Catarinense"> </a> </div>
      <!--mobile-menu-->
      <div class="block absolute right-0 lg:hidden pr-4">
        <button id="nav-toggle"
          class="flex items-center px-3 py-2 border rounded text-white border-white hover:text-gray-800 hover:border-blue-500 appearance-none focus:outline-none">
          <svg class="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <title>Menu</title>
            <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" />
          </svg>
        </button>
      </div>

      <div
        class="w-full flex-grow lg:flex lg:items-center lg:w-auto hidden lg:block mt-2 lg:mt-0 text-black p-4 lg:p-0 z-20"
        id="nav-content">
        <div class="list-reset lg:flex justify-end flex-1 items-center mr-10">
          <ul class="">

            @can('isAdmin')
            <li
              class="block md:inline md:float-left sm:z-50 px-4 text-blue-200 hover:text-white cursor-pointer font-bold text-base tracking-wide">
              <div class="">              <x-jet-dropdown align="left" width="48">
                  <x-slot name="trigger">
                    <a href="#"
                      class="inline text-blue-200 hover:text-white cursor-pointer font-bold text-base tracking-wide">
                      Instituição
                    </a>
                    <svg class="h-4 w-4 inline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                      fill="currentColor">
                      <path fill-rule="evenodd"
                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                        clip-rule="evenodd" />
                    </svg>
                  </x-slot>

                  <x-slot name="content">
                    <x-jet-dropdown-link href="{{ route('admin.campuses.index') }}">
                      Campus
                    </x-jet-dropdown-link>
                    <x-jet-dropdown-link href="{{ route('admin.courses.index') }}">
                      Cursos
                    </x-jet-dropdown-link>
                    <x-jet-dropdown-link href="{{ route('admin.modalities.index') }}">
                      Modalidades
                    </x-jet-dropdown-link>

                  </x-slot>
                </x-jet-dropdown>
              </div>
            </li>


            <li
              class="block md:inline md:float-left sm:z-50 px-4 text-blue-200 hover:text-white cursor-pointer font-bold text-base tracking-wide">
              <div class="">
                <x-jet-dropdown align="left" width="48">
                  <x-slot name="trigger">
                    <a href="#"
                      class="inline text-blue-200 hover:text-white cursor-pointer font-bold text-base tracking-wide">
                      Processo
                    </a>
                    <svg class="h-4 w-4 inline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                      fill="currentColor">
                      <path fill-rule="evenodd"
                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                        clip-rule="evenodd" />
                    </svg>
                  </x-slot>

                  <x-slot name="content">

                    <x-jet-dropdown-link href="{{ route('admin.affirmative-actions.index') }}">
                      Ações Afirmativas
                    </x-jet-dropdown-link>
                    <x-jet-dropdown-link href="{{ route('admin.notices.index') }}">
                      Editais
                    </x-jet-dropdown-link>

                  </x-slot>
                </x-jet-dropdown>
              </div>
            </li>

            <li
              class="block md:inline md:loat-left px-4 text-blue-200 hover:text-white cursor-pointer font-bold text-base tracking-wide">
              <a></a>
            </li>
            <li
              class="block md:inline md:float-left px-4 text-blue-200 hover:text-white cursor-pointer font-bold text-base tracking-wide">
            <a href="{{ route('admin.users.index') }}">
                Usuários
              </a>
            </li>
            @endcan

            <li
              class="hidden md:inline md:float-left sm:z-50 text-blue-200 hover:text-white cursor-pointer font-bold text-base tracking-wide">
              <div class="">
                <x-jet-dropdown align="right" width="48">
                  <x-slot name="trigger">
                    <button
                      class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition duration-150 ease-in-out">
                      <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}"
                        alt="{{ Auth::user()->name }}" title="Meu Perfil" />
                    </button>
                  </x-slot>

                  <x-slot name="content">

                    <x-jet-dropdown-link href="/user/profile">
                      {{ __('Profile') }}
                    </x-jet-dropdown-link>

                    <x-jet-dropdown-link href="{{ route('dashboard') }}">
                      Candidato
                    </x-jet-dropdown-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                      @csrf

                      <x-jet-dropdown-link href="{{ route('logout') }}" onclick="event.preventDefault();
                                                                    this.closest('form').submit();">
                        {{ __('Logout') }}
                      </x-jet-dropdown-link>
                    </form>
                  </x-slot>
                </x-jet-dropdown>
              </div>
            </li>

          </ul>
        </div>

        <!-- Profile mobile -->
        <div class="md:hidden mt-4 pt-4 pb-1 border-t border-gray-200">
          <div class="flex items-center px-4">
            <div class="flex-shrink-0">
              <img class="h-10 w-10 rounded-full" src="{{ Auth::user()->profile_photo_url }}"
                alt="{{ Auth::user()->name }}" />
            </div>

            <div class="ml-3">
              <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
              <div class="font-medium text-sm text-gray-100">{{ Auth::user()->email }}</div>
            </div>
          </div>

          <div class="mt-3 space-y-1">
            <!-- Account Management -->
            <x-jet-responsive-nav-link class="text-blue-200" href="/user/profile"
              :active="request()->routeIs('profile.show')">
              {{ __('Profile') }}
            </x-jet-responsive-nav-link>

            @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
            <x-jet-responsive-nav-link href="/user/api-tokens" :active="request()->routeIs('api-tokens.index')">
              {{ __('API Tokens') }}
            </x-jet-responsive-nav-link>
            @endif

            <!-- Authentication -->
            <form method="POST" action="{{ route('logout') }}">
              @csrf

              <x-jet-responsive-nav-link class="text-blue-200" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                  this.closest('form').submit();">
                {{ __('Logout') }}
              </x-jet-responsive-nav-link>
            </form>
          </div>
        </div>
      </div>
    </div>
  </nav>
