import template from './order-clearance-group-list.html.twig';

const {Component, Mixin} = Shopware;
const {Criteria} = Shopware.Data;

Component.register('order-clearance-group-list', {
    template,

    inject: ['repositoryFactory', 'acl'],

    mixins: [
        Mixin.getByName('listing'),
    ],

    data() {
        return {
            orderClearanceGroups: null,
            isLoading: true,
            sortBy: 'name',
            sortDirection: 'ASC',
            total: 0,
            searchConfigEntity: 'order_clearance_group',
        };
    },

    metaInfo() {
        return {
            title: this.$createTitle(),
        };
    },

    computed: {
        orderClearanceGroupRepository() {
            return this.repositoryFactory.create('order_clearance_group');
        },

        orderClearanceGroupColumns() {
            return [{
                property: 'name',
                dataIndex: 'name',
                allowResize: true,
                routerLink: 'order.clearance.group.detail',
                label: 'order-clearance-group.list.columnName',
                inlineEdit: 'string',
                primary: true,
            },
            {
                property: 'myfavOrgCompany.name',
                dataIndex: 'myfavOrgCompany.name',
                allowResize: true,
                routerLink: 'order.clearance.group.detail',
                label: 'order-clearance-group.list.columnCompanyName',
                primary: true,
            }];
        },

        orderClearanceGroupCriteria() {
            const orderClearanceGroupCriteria = new Criteria(this.page, this.limit);
            orderClearanceGroupFromCriteria.setTerm(this.term);
            orderClearanceGroupFromCriteria.addSorting(Criteria.sort(this.sortBy, this.sortDirection, this.naturalSorting));

            return orderClearanceGroupFromCriteria;
        },
    },

    methods: {
        async getList() {
            this.isLoading = true;

            const criteria = new Criteria();
            criteria.addAssociation('myfavOrgCompany');
            criteria.setPage(1);
            criteria.setLimit(500);
            criteria.setTotalCountMode(2);
            criteria.addSorting( Criteria.sort('name', 'ASC') );

            return this.orderClearanceGroupRepository.search(criteria)
                .then(searchResult => {
                    this.orderClearanceGroups = searchResult;
                    this.total = searchResult.total;
                    this.isLoading = false;
                });

            return [];
        },

        updateTotal({ total }) {
            this.total = total;
        },
    },
});
