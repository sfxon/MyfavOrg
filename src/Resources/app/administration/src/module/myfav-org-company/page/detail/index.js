/*
 * @package inventory
 */

import template from './myfav-org-company-detail.html.twig';
import './myfav-org-company-detail.scss';

const {Component, Mixin} = Shopware;
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
            myfavOrgCompany: null,
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
        identifier() {
            return this.placeholder(this.myfavOrgCompany, 'name');
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
                .get(this.$route.params.id, Shopware.Context.api, this.criteria)
                .then((entity) => {
                    this.myfavOrgCompany = entity;
                    this.myfavOrgCompanyId = entity.id;
                });
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


        onSave() {
            this.isLoading = true;



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

    },
});
