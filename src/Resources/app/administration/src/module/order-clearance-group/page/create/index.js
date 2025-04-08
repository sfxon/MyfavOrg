/*
 * @package inventory
 */

import template from './../detail/order-clearance-group-detail.html.twig';

const {Component, Mixin} = Shopware;
const { Criteria } = Shopware.Data;
const { mapPropertyErrors } = Shopware.Component.getComponentHelper();

Component.register('order-clearance-group-create', {
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
        orderClearanceGroupId: {
            type: String,
            required: false,
            default: null,
        },
    },

    data() {
        return {
            editMode: false,
            orderClearanceGroup: null,
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
            Shopware.ExtensionAPI.publishData({
                id: 'order-clearance-group--detail__orderClearanceGroup',
                path: 'orderClearanceGroup',
                scope: this,
            });
            if (this.orderClearanceGroupId) {
                this.loadEntityData();
                return;
            }

            Shopware.State.commit('context/resetLanguageToDefault');
            this.orderClearanceGroup = this.orderClearanceGroupRepository.create();
        },

        async loadEntityData() {
            this.isLoading = true;

            const [orderClearanceGroupResponse] = await Promise.allSettled([
                this.orderClearanceGroupRepository.get(this.orderClearanceGroupId),
            ]);

            if (orderClearanceGroupResponse.status === 'fulfilled') {
                this.orderClearanceGroup = orderClearanceGroupResponse.value;
            }

            if (orderClearanceGroupResponse.status === 'rejected') {
                this.createNotificationError({
                    message: this.$tc(
                        'global.notification.notificationLoadingDataErrorMessage',
                    ),
                });
            }

            this.isLoading = false;
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

        onSave() {
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
