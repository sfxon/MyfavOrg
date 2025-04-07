import template from './myfav-org-acl-role-attribute.html.twig';
import './myfav-org-acl-role-attribute.scss';
import MyfavOrgAclRoleAttributeApiService from "./../../service/api/myfav-org-acl-role-attribute.api.service.js";

const { Application, Component, Mixin, Service } = Shopware;
const { Criteria } = Shopware.Data;
const { mapPropertyErrors } = Shopware.Component.getComponentHelper();

Component.register('myfav-org-acl-role-attribute', {
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
            accessRightValues: {},
            editMode: true,
            myfavOrgAclRoleAttributeApiService: null,
            myfavOrgAclAttributeGroups: null,
            myfavOrgAclRole: null,
            myfavOrgAclRoleAttributes: null,
            isLoading: false,
            isSaveSuccessful: false,
        };
    },

    props: {
        myfavOrgAclRoleId: {
            type: String,
            required: true,
        },
    },

    metaInfo() {
        return {
            title: this.$createTitle(this.identifier),
        };
    },

    computed: {
        cardTitle() {
            if(this.myfavOrgAclRole === null) {
                return this.$tc('myfav-org-acl-role-attribute.attribute.cardTitleLoading');
            } else {
                return (
                    this.$tc('myfav-org-acl-role-attribute.attribute.cardTitle') +
                    this.myfavOrgAclRole.name +
                    ' | ' +
                    this.$tc('myfav-org-acl-role-attribute.attribute.cardTitleCompany') +
                    this.myfavOrgAclRole.myfavOrgCompany.name
                );
            }
        },

        identifier() {
            return this.placeholder(this.myfavOrgAclRole, 'name');
        },

        myfavOrgAclAttributeGroupCriteria() {
            const criteria = new Criteria(1, 500);
            criteria.addAssociation('myfavOrgAclAttributes');
            criteria.addSorting(Criteria.sort('sortOrder', 'ASC'));
            return criteria;
        },

        myfavOrgAclAttributeGroupRepository() {
            return this.repositoryFactory.create('myfav_org_acl_attribute_group');
        },

        myfavOrgAclRoleCriteria() {
            const criteria = new Criteria(1, 500);
            criteria.addAssociation('myfavOrgCompany');
            criteria.addAssociation('myfavOrgAclRoleAttributes');
            return criteria;
        },

        myfavOrgAclRoleIsLoading() {
            return this.isLoading || this.myfavOrgAclRole == null;
        },

        myfavOrgAclRoleRepository() {
            return this.repositoryFactory.create('myfav_org_acl_role');
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

        aclAttributeExists(myGroup, accessRight) {
            let attributeAccessRight = myGroup.name + '.' + accessRight;

            for(let i = 0, j = myGroup.myfavOrgAclAttributes.length; i < j; i++) {
                if(myGroup.myfavOrgAclAttributes[i].technicalName === attributeAccessRight) {
                    return true;
                }
            }

            return false;
        },

        async loadEntityData() {
            await this.myfavOrgAclRoleRepository
                .get(this.myfavOrgAclRoleId, Shopware.Context.api, this.myfavOrgAclRoleCriteria)
                .then((entity) => {
                    this.myfavOrgAclRole = entity;
                }
            );

            await this.myfavOrgAclAttributeGroupRepository
                .search(this.myfavOrgAclAttributeGroupCriteria, Shopware.Context.api)
                .then((entities) => {
                    this.myfavOrgAclAttributeGroups = entities;
                }
            );

            // Set checkboxes active or inactive.
            for(let i = 0, j = this.myfavOrgAclRole.myfavOrgAclRoleAttributes.length; i < j; i++) {
                let activatedRoleAttribute = this.myfavOrgAclRole.myfavOrgAclRoleAttributes[i];

                for(let k = 0, l = this.myfavOrgAclAttributeGroups.length; k < l; k++) {
                    let tmpGroup = this.myfavOrgAclAttributeGroups[k];

                    for(let x = 0, y = tmpGroup.myfavOrgAclAttributes.length; x < y; x++) {
                        let tmpGroupAttribute = tmpGroup.myfavOrgAclAttributes[x];
                        let tmpGroupAttributeId = tmpGroupAttribute.id;

                        if(tmpGroupAttributeId === activatedRoleAttribute.myfavOrgAclAttributeId) {
                            this.accessRightValues[tmpGroupAttribute.technicalName] = {
                                'groupId': tmpGroup.id,
                                'technicalName': tmpGroupAttribute.technicalName,
                                'myfavOrgAclAttributeId': activatedRoleAttribute.myfavOrgAclAttributeId
                            };
                        }
                    }
                }
            }

            console.log(this.accessRightValues);
        },

        async onSave() {
            this.isLoading = true;

            if(this.myfavOrgAclRoleAttributeApiService == null) {
                const httpClient = Application.getContainer('init')['httpClient'];
                const loginService = Service('loginService');

                this.myfavOrgAclRoleAttributeApiService = new MyfavOrgAclRoleAttributeApiService(
                    httpClient,
                    loginService
                );
            }

            await this.myfavOrgAclRoleAttributeApiService.update(this.myfavOrgAclRoleId, this.accessRightValues);

            this.isLoading = false;
            this.isSaveSuccessful = true;
        },

        onCancel() {
            this.$router.push({ name: 'myfav.org.acl.role.index' });
        },

        roleAccessRightChange(event, myGroup, accessRight) {
            let attributeAccessRight = myGroup.name + '.' + accessRight;
            let myfavOrgAclAttributeId = null;

            for(let i = 0, j = myGroup.myfavOrgAclAttributes.length; i < j; i++) {
                if(myGroup.myfavOrgAclAttributes[i].technicalName === attributeAccessRight) {
                    myfavOrgAclAttributeId = myGroup.myfavOrgAclAttributes[i].id;
                }
            }

            if(event.target.checked === true) {
                this.accessRightValues[attributeAccessRight] = {
                    'groupId': myGroup.id,
                    'technicalName': attributeAccessRight,
                    'myfavOrgAclAttributeId': myfavOrgAclAttributeId
                };
            } else {
                this.$delete(this.accessRightValues, attributeAccessRight);
            }
        },

        roleHasAccessRight(myGroup, accessRight) {
            let attributeAccessRight = myGroup.name + '.' + accessRight;

            if(this.accessRightValues.hasOwnProperty(attributeAccessRight)) {
                return true;
            }

            return false;
        }
    },
});
