/*
 * @package inventory
 */

import template from './../detail/myfav-org-company-detail.html.twig';

const {Component, Mixin} = Shopware;
const { Criteria } = Shopware.Data;
const { mapPropertyErrors } = Shopware.Component.getComponentHelper();

Component.register('myfav-org-company-create', {
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

    props: {
        myfavOrgCompanyId: {
            type: String,
            required: false,
            default: null,
        },
    },

    data() {
        return {
            myfavOrgCompany: null,
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
            Shopware.ExtensionAPI.publishData({
                id: 'myfav--org-company-detail__myfavOrgCompany',
                path: 'myfavOrgCompany',
                scope: this,
            });
            if (this.myfavOrgCompanyId) {
                this.loadEntityData();
                return;
            }

            Shopware.State.commit('context/resetLanguageToDefault');
            this.myfavOrgCompany = this.myfavOrgCompanyRepository.create();
        },

        async loadEntityData() {
            this.isLoading = true;

            const [myfavOrgCompanyResponse] = await Promise.allSettled([
                this.myfavOrgCompanyRepository.get(this.myfavOrgCompanyId),
            ]);

            if (myfavOrgCompanyResponse.status === 'fulfilled') {
                this.myfavOrgCompany = myfavOrgCompanyResponse.value;
            }

            if (myfavOrgCompanyResponse.status === 'rejected') {
                this.createNotificationError({
                    message: this.$tc(
                        'global.notification.notificationLoadingDataErrorMessage',
                    ),
                });
            }

            this.isLoading = false;
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
