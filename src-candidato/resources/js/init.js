    /*dropdown*/

    var navMenuDiv = document.getElementById("nav-content");
    var navMenu = document.getElementById("nav-toggle");

    document.onclick = check;

    function check(e) {
        if(navMenuDiv == null || navMenu == null) return;

        var target = (e && e.target) || (event && event.srcElement);

        //Nav Menu
        if (!checkParent(target, navMenuDiv)) {
            // click NOT on the menu
            if (checkParent(target, navMenu)) {
                // click on the link
                if (navMenuDiv.classList.contains("hidden")) {
                    navMenuDiv.classList.remove("hidden");
                } else {
                    navMenuDiv.classList.add("hidden");
                }
            } else {
                // click both outside link and outside menu, hide menu
                navMenuDiv.classList.add("hidden");
            }
        }
    }

    function checkParent(t, elm) {
        while (t.parentNode) {
            if (t == elm) {
                return true;
            }
            t = t.parentNode;
        }
        return false;
    }

/*masks */
if(window.jQuery){
    $(document).ready(function(){
        $('.mask-cep').mask('00000-000');
        $('.mask-cpf').mask('000.000.000-00', {reverse: true});
        $('.mask-date').mask('00/00/0000');
        $('.mask-money').mask('000.000.000.000.000,00', {reverse: true});

        var phoneMaskFlexible = function (val) {
            return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
          },
          phoneOptions = {
            onKeyPress: function(val, e, field, options) {
                field.mask(phoneMaskFlexible.apply({}, arguments), options);
              }
          };

        $('.mask-phone').mask(phoneMaskFlexible, phoneOptions); 
        
        function toggleContrast(){
            if($("body").hasClass('contrast')){
                console.log("contraste estava ativado: desative");
                $("html").removeClass('contrast');
                $("body").removeClass('contrast');
                $(".container").removeClass('contrast');
                $(".w-full").removeClass('contrast');
                $('label').removeClass('contrast');    
                $('a').removeClass('contrast');
                $('p').removeClass('contrast');  
                $('#logo-header').removeClass('contrast'); 
                $('.logo-color').toggle();    
                $('.logo-white').toggle();  
                $('.border-t-4').removeClass('contrast-border');
                $('.text-xl').removeClass('contrast-highlight');
                $('.text-lg').removeClass('contrast-highlight');
                $('.text-xs').removeClass('contrast');
            }else{
                console.log("contraste estava desativado: ativar");
                $("html").addClass('contrast');
                $("body").addClass('contrast');
                $(".container").addClass('contrast');
                $(".w-full").addClass('contrast');
                $('label').addClass('contrast');
                $('input').css('color','black');
                $('a').addClass('contrast');
                $('p').addClass('contrast');
                $('#logo-header').addClass('contrast'); 
                $('.logo-color').toggle();
                $('.logo-white').toggle();  
                $('.border-t-4').addClass('contrast-border');
                $('.text-xl').addClass('contrast-highlight');
                $('.text-lg').addClass('contrast-highlight');
                $('.text-xs').addClass('contrast');
            }
        }

        $("#bt-contrast").click(function() {
            toggleContrast();
        });
    });
}

