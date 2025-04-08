Shopware.Module.register('order-clearance-group', {
    type: 'plugin',
    name: 'OrderClearanceGroup',
    title: 'order-clearance-group.general.title',
    description: 'order-clearance-group.general.title',
    color: '#F05A29',
    icon: '',

    navigation: [{
        label: 'order-clearance-group.general.menuTitle',
        color: '#F05A29',
        path: 'order.clearance.group.index',
        icon: '',
        parent: 'sw-customer',
        position: 120
    }],

    routes: {
        index: {
            component: 'order-clearance-group-list',
            path: 'index'
        },
        create: {
            component: 'order-clearance-group-create',
            path: 'create',
            meta: {
                parentPath: 'order.clearance.group.index',
            },
        },
        detail: {
            component: 'order-clearance-group-detail',
            path: 'detail/:id',
            meta: {
                parentPath: 'order.clearance.group.index',
            },
        }
    },
});