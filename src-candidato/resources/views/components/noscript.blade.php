<noscript>
    <div
    class="modal fixed w-full h-full bg-red-200 top-0 left-0 flex items-center justify-center">
        <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>
        <div
            class="modal-container bg-white w-11/12 md:max-w-lg mx-auto rounded shadow-lg z-50 overflow-y-auto overscroll-auto">
            <div x-on:click="open = false"
                class="modal-close absolute top-0 right-0 cursor-pointer flex flex-col items-center mt-4 mr-4 text-white text-sm z-50">
                <svg class="fill-current text-white" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                    viewBox="0 0 18 18">
                    <path
                        d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z">
                    </path>
                </svg>                
            </div>
            
            <div class="modal-content py-4 text-left px-6 overflow-y-auto h-auto">
                        <!--Body-->
                <div class="text-red-700">
                  Seu navegador precisa ter suporte a javascript, habilite e atualize a página!
              </div>      
            </div>
        </div>
    </div>
  </noscript>