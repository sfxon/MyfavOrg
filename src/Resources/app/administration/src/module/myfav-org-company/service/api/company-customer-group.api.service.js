const { ApiService } = Shopware.Classes;

export default class CompanyCustomerGroupApiService extends ApiService {
    constructor(httpClient, loginService, apiEndpoint = 'myfavOrgCompanyCustomerGroup') {
        super(httpClient, loginService, apiEndpoint);
        this.name = 'CompanyCustomerGroupApiService'; // I am not sure, what this is really for.
        this.$listener = () => ({});
    }

    /**
     * Fetch the orderDeliveryInternalState for the order given by orderId.
     */
    update(myfavOrgCompanyId, customerGroups) {
        const route = `/myfav/org/company/customer/group/update`;

        return this.httpClient.post(
            route,
            {
                myfavOrgCompanyId: myfavOrgCompanyId,
                customerGroups: customerGroups
            },
            {
                headers: this.getBasicHeaders(),
                responseType: 'json'
            }
        );
    }
}
