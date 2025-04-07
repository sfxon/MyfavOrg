const { ApiService } = Shopware.Classes;

export default class MyfavOrgAclRoleAttributeApiService extends ApiService {
    constructor(httpClient, loginService, apiEndpoint = 'myfavOrgAclRoleAttribute') {
        super(httpClient, loginService, apiEndpoint);
        this.name = 'MyfavOrgAclRoleAttributeApiService'; // I am not sure, what this is really for.
        this.$listener = () => ({});
    }

    /**
     * Update.
     */
    update(myfavOrgAclRoleId, activatedAccessRights) {
        const route = `/myfav/org/acl/role/attribute/update`;

        return this.httpClient.post(
            route,
            {
                myfavOrgAclRoleId: myfavOrgAclRoleId,
                activatedAccessRights: activatedAccessRights
            },
            {
                headers: this.getBasicHeaders(),
                responseType: 'json'
            }
        );
    }
}
