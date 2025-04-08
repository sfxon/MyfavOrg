import template from './order-clearance-group-detail.html.twig';
import './order-clearance-group-detail.scss';

const { Application, Component, Mixin, Service } = Shopware;
const { Criteria } = Shopware.Data;
const { mapPropertyErrors } = Shopware.Component.getComponentHelper();

Component.register('order-clearance-group-detail', {
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
            orderClearanceGroup: null,
            orderClearanceGroupId: null,
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
            return this.placeholder(this.orderClearanceGroup, 'name');
        },

        orderClearanceGroupCriteria() {
            const criteria = new Criteria(1, 500);
            return criteria;
        },

        orderClearanceGroupIsLoading() {
            return this.isLoading || this.orderClearanceGroup == null;
        },

        orderClearanceGroupRepository() {
            return this.repositoryFactory.create('order_clearance_group');
        },

        myfavOrgCompanyId: {
            get() {
                return this.orderClearanceGroup?.myfavOrgCompanyId || null;
            },
            set(value) {
                this.orderClearanceGroup.myfavOrgCompanyId = value;
            }
        },

        ...mapPropertyErrors('orderClearanceGroup', ['name']),
    },

    watch: {
        orderClearanceGroupId() {
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
            this.orderClearanceGroupRepository
                .get(this.$route.params.id, Shopware.Context.api, this.orderClearanceGroupCriteria)
                .then((entity) => {
                    this.orderClearanceGroup = entity;
                    this.orderClearanceGroupId = entity.id;
                }
            );
        },

        abortOnLanguageChange() {
            return this.orderClearanceGroupRepository.hasChanges(this.orderClearanceGroup);
        },

        saveOnLanguageChange() {
            return this.onSave();
        },

        onChangeLanguage() {
            this.loadEntityData();
        },

        async onSave() {
            this.isLoading = true;

            this.orderClearanceGroupRepository.save(this.orderClearanceGroup).then(() => {
                this.isLoading = false;
                this.isSaveSuccessful = true;

                if (this.orderClearanceGroupId === null) {
                    this.$router.push({ name: 'order.clearance.group.index' });
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
            this.$router.push({ name: 'order.clearance.group.index' });
        },
    },
});
