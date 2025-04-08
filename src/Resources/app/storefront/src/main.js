import MyfavOrgEditEmployeePlugin from './myfav-org-edit-employee/myfav-org-edit-employee.plugin';

// Register your plugin via the existing PluginManager
const PluginManager = window.PluginManager;
PluginManager.register('MyfavOrgEditEmployeePlugin', MyfavOrgEditEmployeePlugin, '[myfav-org-edit-employee]');