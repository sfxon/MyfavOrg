import template from './sw-customer-detail-base.html.twig';
import './sw-customer-detail-base.scss';

const { Component } = Shopware;
const { Criteria } = Shopware.Data;

Component.override('sw-customer-detail-base', {
    template,

    inject: ['repositoryFactory'],

    data() {
        return {
            customFieldValue: '',
        };
    },

    computed: {
        myfavOrgAclRoleCriteria() {
            const criteria = new Criteria(1, 500);
            criteria.addFilter(Criteria.equals('myfavOrgCompanyId', this.myfavOrgCompanyId));
            return criteria;
        },

        myfavOrgAclRoleId: {
            get() {
                return this.customer?.extensions?.myfavOrgCustomerExtension?.myfavOrgAclRoleId || null;
            },
            set(value) {
                if(!this.customer.extensions) {
                    this.customer.extensions = {};
                }

                if(!this.customer.extensions.myfavOrgCustomerExtension) {
                    const tmpRepository = this.repositoryFactory.create('myfav_org_customer_data');
                    this.customer.extensions.myfavOrgCustomerExtension = tmpRepository.create(Shopware.Context.api);
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
                    const tmpRepository = this.repositoryFactory.create('myfav_org_customer_data');
                    this.customer.extensions.myfavOrgCustomerExtension = tmpRepository.create(Shopware.Context.api);
                }

                this.customer.extensions.myfavOrgCustomerExtension.myfavOrgCompanyId = value;
            }
        }
    }
});