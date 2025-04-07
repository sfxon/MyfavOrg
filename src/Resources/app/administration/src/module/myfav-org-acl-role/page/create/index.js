/*
 * @package inventory
 */

import template from './../detail/myfav-org-acl-role-detail.html.twig';

const {Component, Mixin} = Shopware;
const { Criteria } = Shopware.Data;
const { mapPropertyErrors } = Shopware.Component.getComponentHelper();

Component.register('myfav-org-acl-role-create', {
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
        myfavOrgAclRoleId: {
            type: String,
            required: false,
            default: null,
        },
    },

    data() {
        return {
            editMode: false,
            myfavOrgAclRole: null,
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
            return this.placeholder(this.myfavOrgAclRole, 'name');
        },

        myfavOrgAclRoleIsLoading() {
            return this.isLoading || this.myfavOrgAclRole == null;
        },

        myfavOrgAclRoleRepository() {
            return this.repositoryFactory.create('myfav_org_acl_role');
        },

        myfavOrgCompanyId: {
            get() {
                return this.myfavOrgAclRole?.myfavOrgCompanyId || null;
            },
            set(value) {
                this.myfavOrgAclRole.myfavOrgCompanyId = value;
            }
        },

        ...mapPropertyErrors('myfavOrgAclRole', ['name']),
    },

    watch: {
        myfavOrgAclRoleId() {
            this.createdComponent();
        },
    },

    created() {
        this.createdComponent();
    },

    methods: {
        createdComponent() {
            Shopware.ExtensionAPI.publishData({
                id: 'myfav--org-acl-role-detail__myfavOrgAclRole',
                path: 'myfavOrgAclRole',
                scope: this,
            });
            if (this.myfavOrgAclRoleId) {
                this.loadEntityData();
                return;
            }

            Shopware.State.commit('context/resetLanguageToDefault');
            this.myfavOrgAclRole = this.myfavOrgAclRoleRepository.create();
        },

        async loadEntityData() {
            this.isLoading = true;

            const [myfavOrgAclRoleResponse] = await Promise.allSettled([
                this.myfavOrgAclRoleRepository.get(this.myfavOrgAclRoleId),
            ]);

            if (myfavOrgAclRoleResponse.status === 'fulfilled') {
                this.myfavOrgAclRole = myfavOrgAclRoleResponse.value;
            }

            if (myfavOrgAclRoleResponse.status === 'rejected') {
                this.createNotificationError({
                    message: this.$tc(
                        'global.notification.notificationLoadingDataErrorMessage',
                    ),
                });
            }

            this.isLoading = false;
        },

        abortOnLanguageChange() {
            return this.myfavOrgAclRoleRepository.hasChanges(this.myfavOrgAclRole);
        },

        saveOnLanguageChange() {
            return this.onSave();
        },

        onChangeLanguage() {
            this.loadEntityData();
        },

        onSave() {
            this.isLoading = true;

            this.myfavOrgAclRoleRepository.save(this.myfavOrgAclRole).then(() => {
                this.isLoading = false;
                this.isSaveSuccessful = true;
                if (this.myfavOrgAclRoleId === null) {
                    this.$router.push({ name: 'myfav.org.acl.role.index' });
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
            this.$router.push({ name: 'myfav.org.acl.role.index' });
        },

    },
});
