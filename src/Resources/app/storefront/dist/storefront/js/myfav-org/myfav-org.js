(()=>{"use strict";let{PluginBaseClass:e}=window;window.PluginManager.register("MyfavOrgEditEmployeePlugin",class extends e{init(){document.getElementById("useAlternativeShippingAddress").addEventListener("click",function(e){let t=document.getElementById("myfav-alternative-shipping-address");e.target.checked?t.style.display="block":t.style.display="none"})}},"[myfav-org-edit-employee]")})();