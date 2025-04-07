import template from './myfav-org-acl-role-list.html.twig';

const {Component, Mixin} = Shopware;
const {Criteria} = Shopware.Data;

Component.register('myfav-org-acl-role-list', {
    template,

    inject: ['repositoryFactory', 'acl'],

    mixins: [
        Mixin.getByName('listing'),
    ],

    data() {
        return {
            myfavOrgAclRoles: null,
            isLoading: true,
            sortBy: 'name',
            sortDirection: 'ASC',
            total: 0,
            searchConfigEntity: 'myfav_org_acl_role',
        };
    },

    metaInfo() {
        return {
            title: this.$createTitle(),
        };
    },

    computed: {
        myfavOrgAclRoleRepository() {
            return this.repositoryFactory.create('myfav_org_acl_role');
        },

        myfavOrgAclRoleColumns() {
            return [{
                property: 'name',
                dataIndex: 'name',
                allowResize: true,
                routerLink: 'myfav.org.acl.role.detail',
                label: 'myfav-org-acl-role.list.columnName',
                inlineEdit: 'string',
                primary: true,
            },
            {
                property: 'myfavOrgCompany.name',
                dataIndex: 'myfavOrgCompany.name',
                allowResize: true,
                routerLink: 'myfav.org.acl.role.detail',
                label: 'myfav-org-acl-role.list.columnCompanyName',
                primary: true,
            }];
        },

        myfavOrgAclRoleCriteria() {
            const myfavOrgAclRoleCriteria = new Criteria(this.page, this.limit);
            myfavOrgAclRoleFromCriteria.setTerm(this.term);
            myfavOrgAclRoleFromCriteria.addSorting(Criteria.sort(this.sortBy, this.sortDirection, this.naturalSorting));

            return myfavOrgAclRoleFromCriteria;
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

            return this.myfavOrgAclRoleRepository.search(criteria)
                .then(searchResult => {
                    this.myfavOrgAclRoles = searchResult;
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
