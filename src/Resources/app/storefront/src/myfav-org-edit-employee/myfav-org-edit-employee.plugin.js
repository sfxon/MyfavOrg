const { PluginBaseClass } = window;

export default class MyfavOrgEditEmployeePlugin extends PluginBaseClass {
    init() {
        let alternativeShippingAddressCheckboxElement = document.getElementById('useAlternativeShippingAddress');

        alternativeShippingAddressCheckboxElement.addEventListener('click', function(event) {
            let tmpContainerElement = document.getElementById('myfav-alternative-shipping-address');

            if(event.target.checked) {
                tmpContainerElement.style.display = 'block';
            } else {
                tmpContainerElement.style.display = 'none';
            }
        });
    }
}