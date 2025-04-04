Shopware.Module.register('myfav-org-company', {
    type: 'plugin',
    name: 'MyfavOrgCompany',
    title: 'myfav-org-company.general.title',
    description: 'myfav-org-company.general.title',
    color: '#F05A29',
    icon: '',

    navigation: [{
        label: 'myfav-org-company.general.menuTitle',
        color: '#F05A29',
        path: 'myfav.org.company.index',
        icon: '',
        parent: 'sw-customer',
        position: 100
    }],

    routes: {
        index: {
            component: 'myfav-org-company-list',
            path: 'index'
        },
        create: {
            component: 'myfav-org-company-create',
            path: 'create',
            meta: {
                parentPath: 'myfav.org.company.index',
            },
        },
        detail: {
            component: 'myfav-org-company-detail',
            path: 'detail/:id',
            meta: {
                parentPath: 'myfav.org.company.index',
            },
        }
    },
});