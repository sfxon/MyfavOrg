import template from './sw-customer-detail-base.html.twig';
import './sw-customer-detail-base.scss';

const { Component } = Shopware;

Component.override('sw-customer-detail-base', {
    template,

    data() {
        return {
            customFieldValue: '',
        };
    },

    computed: {
        myfavOrgAclRoleId: {
            get() {
                return this.customer?.extensions?.myfavOrgCustomerExtension?.myfavOrgAclRoleId || null;
            },
            set(value) {
                if(!this.customer.extensions) {
                    this.customer.extensions = {};
                }

                if(!this.customer.extensions.myfavOrgCustomerExtension) {
                    this.customer.extensions.myfavOrgCustomerExtension = {};
                }

                this.customer.extensions.myfavOrgCustomerExtension.myfavOrgAclRoleId = value;
            }
        },
        myfavOrgCompanyId: {
            get() {
                return this.customer?.extensions?.myfavOrgCustomerExtension?.myfavOrgCompanyId || null;
            },
            set(value) {
                if(!this.customer.extensions) {
                    this.customer.extensions = {};
                }

                if(!this.customer.extensions.myfavOrgCustomerExtension) {
                    this.customer.extensions.myfavOrgCustomerExtension = {};
                }

                this.customer.extensions.myfavOrgCustomerExtension.myfavOrgCompanyId = value;
            }
        }
    }
});