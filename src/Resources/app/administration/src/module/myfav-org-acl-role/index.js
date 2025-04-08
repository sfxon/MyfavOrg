Shopware.Module.register('myfav-org-acl-role', {
    type: 'plugin',
    name: 'MyfavOrgAclRole',
    title: 'myfav-org-acl-role.general.title',
    description: 'myfav-org-acl-role.general.title',
    color: '#F05A29',
    icon: '',

    navigation: [{
        label: 'myfav-org-acl-role.general.menuTitle',
        color: '#F05A29',
        path: 'myfav.org.acl.role.index',
        icon: '',
        parent: 'sw-customer',
        position: 110
    }],

    routes: {
        index: {
            component: 'myfav-org-acl-role-list',
            path: 'index'
        },
        create: {
            component: 'myfav-org-acl-role-create',
            path: 'create',
            meta: {
                parentPath: 'myfav.org.acl.role.index',
            },
        },
        detail: {
            component: 'myfav-org-acl-role-detail',
            path: 'detail/:id',
            meta: {
                parentPath: 'myfav.org.acl.role.index',
            },
        }
    },
});