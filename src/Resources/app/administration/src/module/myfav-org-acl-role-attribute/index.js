Shopware.Module.register('myfav-org-acl-role-attribute', {
    type: 'plugin',
    name: 'MyfavOrgAclRoleAttribute',
    title: 'myfav-org-acl-role-attribute.general.title',
    description: 'myfav-org-acl-role-attribute.general.title',
    color: '#F05A29',
    icon: '',

    routes: {
        attribute: {
            component: 'myfav-org-acl-role-attribute',
            path: 'attribute/:myfavOrgAclRoleId',
            meta: {
                parentPath: 'myfav.org.acl.role.index',
            },
            props: {
                default(route) {
                    return {
                        myfavOrgAclRoleId: route.params.myfavOrgAclRoleId
                    };
                },
            },
        },
    },
});