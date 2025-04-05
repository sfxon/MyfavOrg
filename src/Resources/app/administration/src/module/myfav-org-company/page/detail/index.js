import template from './myfav-org-company-detail.html.twig';
import './myfav-org-company-detail.scss';
import CompanyCustomerGroupApiService from "./../../service/api/company-customer-group.api.service.js";

const { Application, Component, Mixin, Service } = Shopware;
const { Criteria } = Shopware.Data;
const { mapPropertyErrors } = Shopware.Component.getComponentHelper();

Component.register('myfav-org-company-detail', {
    template,

    inject: ['repositoryFactory'],

    mixins: [
        Mixin.getByName('placeholder'),
        Mixin.getByName('notification'),
    ],

    shortcuts: {
        'SYSTEMKEY+S': 'onSave',
        ESCAPE: 'onCancel',
    },

    data() {
        return {
            companyCustomerGroupApiService: null,
            myfavOrgCompany: null,
            myfavOrgCompanyCustomerGroupCollection: new Shopware.Data.EntityCollection('collection', 'collection', {}, null, []),
            myfavOrgCompanyId: null,
            isLoading: false,
            isSaveSuccessful: false,
        };
    },

    metaInfo() {
        return {
            title: this.$createTitle(this.identifier),
        };
    },

    computed: {
        customerGroupRepository() {
            return this.repositoryFactory.create('customer_group');
        },

        customerGroupCriteria() {
            const criteria = new Criteria(1, 500);
            return criteria;
        },

        identifier() {
            return this.placeholder(this.myfavOrgCompany, 'name');
        },

        myfavOrgCompanyCriteria() {
            const criteria = new Criteria(1, 500);
            return criteria;
        },

        myfavOrgCompanyIsLoading() {
            return this.isLoading || this.myfavOrgCompany == null;
        },

        myfavOrgCompanyRepository() {
            return this.repositoryFactory.create('myfav_org_company');
        },

        ...mapPropertyErrors('myfavOrgCompany', ['name']),
    },

    watch: {
        myfavOrgCompanyId() {
            this.createdComponent();
        },
    },

    created() {
        this.createdComponent();
    },

    methods: {
        createdComponent() {
            this.isLoading = true;
            this.loadEntityData();
            this.isLoading = false;
        },

        async loadEntityData() {
            this.myfavOrgCompanyRepository
                .get(this.$route.params.id, Shopware.Context.api, this.myfavOrgCompanyCriteria)
                .then((entity) => {
                    this.myfavOrgCompany = entity;
                    this.myfavOrgCompanyId = entity.id;
                }
            );

            if(this.myfavOrgCompanyId !== null) {
                let myfavOrgCompanyCustomerGroups = await this.searchMyfavOrgCompanyCustomerGroups();
                this.myfavOrgCompanyCustomerGroupCollection = new Shopware.Data.EntityCollection('collection', 'collection', {}, null, []);

                if(myfavOrgCompanyCustomerGroups !== null) {
                    for(let i = 0, j = myfavOrgCompanyCustomerGroups.length; i < j; i++) {
                        this.myfavOrgCompanyCustomerGroupCollection.add(myfavOrgCompanyCustomerGroups[i].customerGroup);
                    }
                }
            }
        },

        abortOnLanguageChange() {
            return this.myfavOrgCompanyRepository.hasChanges(this.myfavOrgCompany);
        },

        saveOnLanguageChange() {
            return this.onSave();
        },

        onChangeLanguage() {
            this.loadEntityData();
        },

        async onSave() {
            this.isLoading = true;

            if(this.myfavOrgCompanyId !== null) {
                // Init API-Service for updating company-customer-group relations in the database.
                if(this.companyCustomerGroupApiService == null) {
                    const httpClient = Application.getContainer('init')['httpClient'];
                    const loginService = Service('loginService');

                    this.companyCustomerGroupApiService = new CompanyCustomerGroupApiService(
                        httpClient,
                        loginService
                    );
                }

                let customerGroupIds = [];

                for(let i = 0, j = this.myfavOrgCompanyCustomerGroupCollection.length; i < j; i++) {
                    customerGroupIds.push(this.myfavOrgCompanyCustomerGroupCollection[i].id);
                }

                await this.companyCustomerGroupApiService.update(this.myfavOrgCompanyId, customerGroupIds);
            }

            this.myfavOrgCompanyRepository.save(this.myfavOrgCompany).then(() => {
                this.isLoading = false;
                this.isSaveSuccessful = true;

                if (this.myfavOrgCompanyId === null) {
                    this.$router.push({ name: 'myfav.org.company.index' });
                    return;
                }

                this.loadEntityData();
            }).catch((exception) => {
                this.isLoading = false;
                this.createNotificationError({
                    message: this.$tc(
                        'global.notification.notificationSaveErrorMessageRequiredFieldsInvalid',
                    ),
                });
                throw exception;
            });
        },

        onCancel() {
            this.$router.push({ name: 'myfav.org.company.index' });
        },

        async searchMyfavOrgCompanyCustomerGroups() {
            let criteria = new Criteria(1, 500); // max 500, ggf. paginieren
            criteria.addAssociation('customerGroup');
            criteria.addFilter(Criteria.equals('myfavOrgCompanyId', this.myfavOrgCompanyId));

            let repository = this.repositoryFactory.create('myfav_org_company_customer_group');
            let result = await repository.search(criteria, this.context);

            return result;
        },
    },
});
