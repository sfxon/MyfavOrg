import template from './myfav-org-acl-role-detail.html.twig';
import './myfav-org-acl-role-detail.scss';

const { Application, Component, Mixin, Service } = Shopware;
const { Criteria } = Shopware.Data;
const { mapPropertyErrors } = Shopware.Component.getComponentHelper();

Component.register('myfav-org-acl-role-detail', {
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
            editMode: true,
            myfavOrgAclRole: null,
            myfavOrgAclRoleId: null,
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

        myfavOrgAclRoleCriteria() {
            const criteria = new Criteria(1, 500);
            return criteria;
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
            this.isLoading = true;
            this.loadEntityData();
            this.isLoading = false;
        },

        async loadEntityData() {
            this.myfavOrgAclRoleRepository
                .get(this.$route.params.id, Shopware.Context.api, this.myfavOrgAclRoleCriteria)
                .then((entity) => {
                    this.myfavOrgAclRole = entity;
                    this.myfavOrgAclRoleId = entity.id;
                }
            );
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

        async onSave() {
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
