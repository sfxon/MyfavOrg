(function(){var t={879:function(){},610:function(){Shopware.Module.register("myfav-org-company",{type:"plugin",name:"MyfavOrgCompany",title:"myfav-org-company.general.title",description:"myfav-org-company.general.title",color:"#F05A29",icon:"",navigation:[{label:"myfav-org-company.general.menuTitle",color:"#F05A29",path:"myfav.org.company.index",icon:"",parent:"sw-customer",position:100}],routes:{index:{component:"myfav-org-company-list",path:"index"},create:{component:"myfav-org-company-create",path:"create",meta:{parentPath:"myfav.org.company.index"}},detail:{component:"myfav-org-company-detail",path:"detail/:id",meta:{parentPath:"myfav.org.company.index"}}}})},485:function(t,e,a){var r=a(879);r.__esModule&&(r=r.default),"string"==typeof r&&(r=[[t.id,r,""]]),r.locals&&(t.exports=r.locals),a(346).Z("4eb9bc37",r,!0,{})},346:function(t,e,a){"use strict";function r(t,e){for(var a=[],r={},n=0;n<e.length;n++){var o=e[n],i=o[0],s={id:t+":"+n,css:o[1],media:o[2],sourceMap:o[3]};r[i]?r[i].parts.push(s):a.push(r[i]={id:i,parts:[s]})}return a}a.d(e,{Z:function(){return d}});var n="undefined"!=typeof document;if("undefined"!=typeof DEBUG&&DEBUG&&!n)throw Error("vue-style-loader cannot be used in a non-browser environment. Use { target: 'node' } in your Webpack config to indicate a server-rendering environment.");var o={},i=n&&(document.head||document.getElementsByTagName("head")[0]),s=null,m=0,p=!1,l=function(){},y=null,c="data-vue-ssr-id",u="undefined"!=typeof navigator&&/msie [6-9]\b/.test(navigator.userAgent.toLowerCase());function d(t,e,a,n){p=a,y=n||{};var i=r(t,e);return g(i),function(e){for(var a=[],n=0;n<i.length;n++){var s=o[i[n].id];s.refs--,a.push(s)}e?g(i=r(t,e)):i=[];for(var n=0;n<a.length;n++){var s=a[n];if(0===s.refs){for(var m=0;m<s.parts.length;m++)s.parts[m]();delete o[s.id]}}}}function g(t){for(var e=0;e<t.length;e++){var a=t[e],r=o[a.id];if(r){r.refs++;for(var n=0;n<r.parts.length;n++)r.parts[n](a.parts[n]);for(;n<a.parts.length;n++)r.parts.push(h(a.parts[n]));r.parts.length>a.parts.length&&(r.parts.length=a.parts.length)}else{for(var i=[],n=0;n<a.parts.length;n++)i.push(h(a.parts[n]));o[a.id]={id:a.id,refs:1,parts:i}}}}function f(){var t=document.createElement("style");return t.type="text/css",i.appendChild(t),t}function h(t){var e,a,r=document.querySelector("style["+c+'~="'+t.id+'"]');if(r){if(p)return l;r.parentNode.removeChild(r)}if(u){var n=m++;e=C.bind(null,r=s||(s=f()),n,!1),a=C.bind(null,r,n,!0)}else e=O.bind(null,r=f()),a=function(){r.parentNode.removeChild(r)};return e(t),function(r){r?(r.css!==t.css||r.media!==t.media||r.sourceMap!==t.sourceMap)&&e(t=r):a()}}var v=function(){var t=[];return function(e,a){return t[e]=a,t.filter(Boolean).join("\n")}}();function C(t,e,a,r){var n=a?"":r.css;if(t.styleSheet)t.styleSheet.cssText=v(e,n);else{var o=document.createTextNode(n),i=t.childNodes;i[e]&&t.removeChild(i[e]),i.length?t.insertBefore(o,i[e]):t.appendChild(o)}}function O(t,e){var a=e.css,r=e.media,n=e.sourceMap;if(r&&t.setAttribute("media",r),y.ssrId&&t.setAttribute(c,e.id),n&&(a+="\n/*# sourceURL="+n.sources[0]+" */\n/*# sourceMappingURL=data:application/json;base64,"+btoa(unescape(encodeURIComponent(JSON.stringify(n))))+" */"),t.styleSheet)t.styleSheet.cssText=a;else{for(;t.firstChild;)t.removeChild(t.firstChild);t.appendChild(document.createTextNode(a))}}}},e={};function a(r){var n=e[r];if(void 0!==n)return n.exports;var o=e[r]={id:r,exports:{}};return t[r](o,o.exports,a),o.exports}a.d=function(t,e){for(var r in e)a.o(e,r)&&!a.o(t,r)&&Object.defineProperty(t,r,{enumerable:!0,get:e[r]})},a.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},a.p="bundles/myfavorg/",window?.__sw__?.assetPath&&(a.p=window.__sw__.assetPath+"/bundles/myfavorg/"),function(){"use strict";a(610);let{Component:t,Mixin:e}=Shopware,{Criteria:r}=Shopware.Data;t.register("myfav-org-company-list",{template:'<sw-page class="myfavOrgCompany__list">\r\n    <template #smart-bar-header>\r\n        <h2>\r\n            {{ $tc(\'myfav-org-company.list.title\') }}\r\n        </h2>\r\n    </template>\r\n\r\n    <template #smart-bar-actions>\r\n        \r\n        <sw-button\r\n            :router-link="{ name: \'myfav.org.company.create\' }"\r\n            class="myfav-org-company-list__add-myfav-org-company"\r\n            variant="primary"\r\n        >\r\n            {{ $tc(\'myfav-org-company.list.buttonAdd\') }}\r\n        </sw-button>\r\n    </template>\r\n\r\n    <template #content>\r\n        <sw-entity-listing\r\n            v-if="entitySearchable"\r\n            class="myfav-org-company-list__grid"\r\n                detailRoute="myfav.org.company.detail"\r\n                :is-loading="isLoading"\r\n                :columns="myfavOrgCompanyColumns"\r\n                :repository="myfavOrgCompanyRepository"\r\n                :items="myfavOrgCompanies"\r\n                :criteria-limit="limit"\r\n                :sort-by="currentSortBy"\r\n                :sort-direction="sortDirection"\r\n                :disable-data-fetching="true"\r\n                identifier="myfav-org-company-form-list"\r\n                @update-records="updateTotal"\r\n                @page-change="onPageChange"\r\n                @column-sort="onSortColumn"\r\n            >\r\n            </sw-entity-listing>\r\n    </template>\r\n</sw-page>',inject:["repositoryFactory","acl"],mixins:[e.getByName("listing")],data(){return{myfavOrgCompanies:null,isLoading:!0,sortBy:"name",sortDirection:"ASC",total:0,searchConfigEntity:"myfav_org_company"}},metaInfo(){return{title:this.$createTitle()}},computed:{myfavOrgCompanyRepository(){return this.repositoryFactory.create("myfav_org_company")},myfavOrgCompanyColumns(){return[{property:"name",dataIndex:"name",allowResize:!0,routerLink:"myfav.org.company.detail",label:"myfav-org-company.list.columnName",inlineEdit:"string",primary:!0}]},myfavOrgCompanyCriteria(){return new r(this.page,this.limit),myfavOrgCompanyFromCriteria.setTerm(this.term),myfavOrgCompanyFromCriteria.addSorting(r.sort(this.sortBy,this.sortDirection,this.naturalSorting)),myfavOrgCompanyFromCriteria}},methods:{async getList(){this.isLoading=!0;let t=new r;return t.setPage(1),t.setLimit(500),t.setTotalCountMode(2),t.addSorting(r.sort("name","ASC")),this.myfavOrgCompanyRepository.search(t).then(t=>{this.myfavOrgCompanies=t,this.total=t.total,this.isLoading=!1})},updateTotal({total:t}){this.total=t}}});var n='\r\n{% block myfav_org_company_detail %}\r\n<sw-page class="myfav-org-company-detail">\r\n    <template #smart-bar-header>\r\n        <h2>{{ placeholder(myfavOrgCompany, \'name\', $tc(\'myfav-org-company.detail.title\')) }}</h2>\r\n    </template>\r\n\r\n     <template #smart-bar-actions>\r\n        <sw-button\r\n            :disabled="myfavOrgCompanyIsLoading"\r\n            @click="onCancel"\r\n        >\r\n            {{ $tc(\'myfav-org-company.detail.buttonCancel\') }}\r\n        </sw-button>\r\n\r\n        <sw-button-process\r\n            v-model="isSaveSuccessful"\r\n            class="myfav-org-company-detail__save-action"\r\n            :is-loading="isLoading"\r\n            variant="primary"\r\n            @click.prevent="onSave"\r\n        >\r\n            {{ $tc(\'myfav-org-company.detail.buttonSave\') }}\r\n        </sw-button-process>\r\n\r\n    </template>\r\n\r\n    <template #content>\r\n        <sw-card-view>\r\n            <template v-if="myfavOrgCompanyIsLoading">\r\n                <sw-skeleton variant="detail-bold" />\r\n                <sw-skeleton />\r\n            </template>\r\n\r\n            <template v-else>\r\n                <sw-card\r\n                    position-identifier="myfav-org-company-detail-basic-info"\r\n                    :title="$tc(\'myfav-org-company.detail.cardTitle\')"\r\n                >\r\n                    <sw-text-field\r\n                        v-model:value="myfavOrgCompany.name"\r\n                        type="text"\r\n                        :label="$tc(\'myfav-org-company.detail.labelName\')"\r\n                        name="name"\r\n                        validation="required"\r\n                        required\r\n                    />\r\n                </sw-card>\r\n\r\n                <sw-card v-if="myfavOrgCompanyId !== null"\r\n                    position-identifier="myfav-org-company-detail-company-settings"\r\n                    :title="$tc(\'myfav-org-company.detail.cardTitleCompanySettings\')"\r\n                >\r\n                    <sw-entity-multi-select\r\n                        entityName="customer_group"\r\n                        v-model:entity-collection="myfavOrgCompanyCustomerGroupCollection"\r\n                        :criteria="customerGroupCriteria"\r\n                        :label="$tc(\'myfav-org-company.detail.labelCustomerGroupMultiSelect\')"\r\n                    ></sw-entity-multi-select>\r\n                </sw-card>\r\n            </template>\r\n        </sw-card-view>\r\n    </template>\r\n</sw-page>\r\n{% endblock %}\r\n';let{Component:o,Mixin:i}=Shopware,{Criteria:s}=Shopware.Data,{mapPropertyErrors:m}=Shopware.Component.getComponentHelper();o.register("myfav-org-company-create",{template:n,inject:["repositoryFactory"],mixins:[i.getByName("placeholder"),i.getByName("notification")],shortcuts:{"SYSTEMKEY+S":"onSave",ESCAPE:"onCancel"},props:{myfavOrgCompanyId:{type:String,required:!1,default:null}},data(){return{myfavOrgCompany:null,isLoading:!1,isSaveSuccessful:!1}},metaInfo(){return{title:this.$createTitle(this.identifier)}},computed:{identifier(){return this.placeholder(this.myfavOrgCompany,"name")},myfavOrgCompanyIsLoading(){return this.isLoading||null==this.myfavOrgCompany},myfavOrgCompanyRepository(){return this.repositoryFactory.create("myfav_org_company")},...m("myfavOrgCompany",["name"])},watch:{myfavOrgCompanyId(){this.createdComponent()}},created(){this.createdComponent()},methods:{createdComponent(){if(Shopware.ExtensionAPI.publishData({id:"myfav--org-company-detail__myfavOrgCompany",path:"myfavOrgCompany",scope:this}),this.myfavOrgCompanyId){this.loadEntityData();return}Shopware.State.commit("context/resetLanguageToDefault"),this.myfavOrgCompany=this.myfavOrgCompanyRepository.create()},async loadEntityData(){this.isLoading=!0;let[t]=await Promise.allSettled([this.myfavOrgCompanyRepository.get(this.myfavOrgCompanyId)]);"fulfilled"===t.status&&(this.myfavOrgCompany=t.value),"rejected"===t.status&&this.createNotificationError({message:this.$tc("global.notification.notificationLoadingDataErrorMessage")}),this.isLoading=!1},abortOnLanguageChange(){return this.myfavOrgCompanyRepository.hasChanges(this.myfavOrgCompany)},saveOnLanguageChange(){return this.onSave()},onChangeLanguage(){this.loadEntityData()},onSave(){this.isLoading=!0,this.myfavOrgCompanyRepository.save(this.myfavOrgCompany).then(()=>{if(this.isLoading=!1,this.isSaveSuccessful=!0,null===this.myfavOrgCompanyId){this.$router.push({name:"myfav.org.company.index"});return}this.loadEntityData()}).catch(t=>{throw this.isLoading=!1,this.createNotificationError({message:this.$tc("global.notification.notificationSaveErrorMessageRequiredFieldsInvalid")}),t})},onCancel(){this.$router.push({name:"myfav.org.company.index"})}}}),a(485);let{ApiService:p}=Shopware.Classes;class l extends p{constructor(t,e,a="myfavOrgCompanyCustomerGroup"){super(t,e,a),this.name="CompanyCustomerGroupApiService",this.$listener=()=>({})}update(t,e){return this.httpClient.post("/myfav/org/company/customer/group/update",{myfavOrgCompanyId:t,customerGroups:e},{headers:this.getBasicHeaders(),responseType:"json"})}}let{Application:y,Component:c,Mixin:u,Service:d}=Shopware,{Criteria:g}=Shopware.Data,{mapPropertyErrors:f}=Shopware.Component.getComponentHelper();c.register("myfav-org-company-detail",{template:n,inject:["repositoryFactory"],mixins:[u.getByName("placeholder"),u.getByName("notification")],shortcuts:{"SYSTEMKEY+S":"onSave",ESCAPE:"onCancel"},data(){return{companyCustomerGroupApiService:null,myfavOrgCompany:null,myfavOrgCompanyCustomerGroupCollection:new Shopware.Data.EntityCollection("collection","collection",{},null,[]),myfavOrgCompanyId:null,isLoading:!1,isSaveSuccessful:!1}},metaInfo(){return{title:this.$createTitle(this.identifier)}},computed:{customerGroupRepository(){return this.repositoryFactory.create("customer_group")},customerGroupCriteria(){return new g(1,500)},identifier(){return this.placeholder(this.myfavOrgCompany,"name")},myfavOrgCompanyCriteria(){return new g(1,500)},myfavOrgCompanyIsLoading(){return this.isLoading||null==this.myfavOrgCompany},myfavOrgCompanyRepository(){return this.repositoryFactory.create("myfav_org_company")},...f("myfavOrgCompany",["name"])},watch:{myfavOrgCompanyId(){this.createdComponent()}},created(){this.createdComponent()},methods:{createdComponent(){this.isLoading=!0,this.loadEntityData(),this.isLoading=!1},async loadEntityData(){if(this.myfavOrgCompanyRepository.get(this.$route.params.id,Shopware.Context.api,this.myfavOrgCompanyCriteria).then(t=>{this.myfavOrgCompany=t,this.myfavOrgCompanyId=t.id}),null!==this.myfavOrgCompanyId){let t=await this.searchMyfavOrgCompanyCustomerGroups();if(this.myfavOrgCompanyCustomerGroupCollection=new Shopware.Data.EntityCollection("collection","collection",{},null,[]),null!==t)for(let e=0,a=t.length;e<a;e++)this.myfavOrgCompanyCustomerGroupCollection.add(t[e].customerGroup)}},abortOnLanguageChange(){return this.myfavOrgCompanyRepository.hasChanges(this.myfavOrgCompany)},saveOnLanguageChange(){return this.onSave()},onChangeLanguage(){this.loadEntityData()},async onSave(){if(this.isLoading=!0,null!==this.myfavOrgCompanyId){if(null==this.companyCustomerGroupApiService){let t=y.getContainer("init").httpClient,e=d("loginService");this.companyCustomerGroupApiService=new l(t,e)}let t=[];for(let e=0,a=this.myfavOrgCompanyCustomerGroupCollection.length;e<a;e++)t.push(this.myfavOrgCompanyCustomerGroupCollection[e].id);await this.companyCustomerGroupApiService.update(this.myfavOrgCompanyId,t)}this.myfavOrgCompanyRepository.save(this.myfavOrgCompany).then(()=>{if(this.isLoading=!1,this.isSaveSuccessful=!0,null===this.myfavOrgCompanyId){this.$router.push({name:"myfav.org.company.index"});return}this.loadEntityData()}).catch(t=>{throw this.isLoading=!1,this.createNotificationError({message:this.$tc("global.notification.notificationSaveErrorMessageRequiredFieldsInvalid")}),t})},onCancel(){this.$router.push({name:"myfav.org.company.index"})},async searchMyfavOrgCompanyCustomerGroups(){let t=new g(1,500);t.addAssociation("customerGroup"),t.addFilter(g.equals("myfavOrgCompanyId",this.myfavOrgCompanyId));let e=this.repositoryFactory.create("myfav_org_company_customer_group");return await e.search(t,this.context)}}})}()})();