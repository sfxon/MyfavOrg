import template from './myfav-org-company-list.html.twig';

const {Component, Mixin} = Shopware;
const {Criteria} = Shopware.Data;

Component.register('myfav-org-company-list', {
    template,

    inject: ['repositoryFactory', 'acl'],

    mixins: [
        Mixin.getByName('listing'),
    ],

    data() {
        return {
            myfavOrgCompanies: null,
            isLoading: true,
            sortBy: 'name',
            sortDirection: 'ASC',
            total: 0,
            searchConfigEntity: 'myfav_org_company',
        };
    },

    metaInfo() {
        return {
            title: this.$createTitle(),
        };
    },

    computed: {
        myfavOrgCompanyRepository() {
            return this.repositoryFactory.create('myfav_org_company');
        },

        myfavOrgCompanyColumns() {
            return [{
                property: 'name',
                dataIndex: 'name',
                allowResize: true,
                routerLink: 'myfav.org.company.detail',
                label: 'myfav-org-company.list.columnName',
                inlineEdit: 'string',
                primary: true,
            }];
        },

        myfavOrgCompanyCriteria() {
            const myfavOrgCompanyCriteria = new Criteria(this.page, this.limit);
            myfavOrgCompanyFromCriteria.setTerm(this.term);
            myfavOrgCompanyFromCriteria.addSorting(Criteria.sort(this.sortBy, this.sortDirection, this.naturalSorting));

            return myfavOrgCompanyFromCriteria;
        },
    },

    methods: {
        async getList() {
            this.isLoading = true;

            const criteria = new Criteria();
            criteria.setPage(1);
            criteria.setLimit(500);
            criteria.setTotalCountMode(2);
            criteria.addSorting( Criteria.sort('name', 'ASC') );

            return this.myfavOrgCompanyRepository.search(criteria)
                .then(searchResult => {
                    this.myfavOrgCompanies = searchResult;
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
