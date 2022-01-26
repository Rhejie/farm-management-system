import Vue from "vue";
import VueRouter from "vue-router";


Vue.use(VueRouter);

export default new VueRouter({
    linkActiveClass: "active",
    linkExactActiveClass: "",
    scrollBehavior() {
        return { x: 0, y: 0 };
    },
    routes: [
        {
            path: '/employee',
            name: 'Employee Main Component',
            props: true,
            component: () => import('../components/employee/EmployeeMainComponent.vue'),
            children: [
                {
                    path: '/employees',
                    name: 'Employee List',
                    props: true,
                    component: () => import('../components/employee/EmployeeIndex.vue')
                },
                {
                    path: '/employee-details/:id',
                    name: 'Employee Details',
                    props: true,
                    component: () => import('../components/employee/partials/EmpoyeeDetails.vue')
                }
            ]
        },
        {
            path: '/category',
            name: 'Category Main Component',
            props: true,
            component: () => import('../components/Category/CategoryMainComponent.vue'),
            children: [
                {
                    path: '/categories',
                    name: 'Category List',
                    props: false,
                    component: () => import('../components/Category/CategoryIndex.vue')
                }
            ]
        },
        {
            path: '/item',
            name: 'Item Main Component',
            props: true,
            component: () => import('../components/item/ItemMainComponent.vue'),
            children: [
                {
                    path: '/items',
                    name: 'Item List',
                    props: false,
                    component: () => import('../components/item/ItemIndex.vue')
                }
            ]
        },
        {
            path: '/stock',
            name: 'Stock Main Component',
            props: true,
            component: () => import('../components/stock/StockMainComponent.vue'),
            children: [
                {
                    path: '/stocks',
                    name: 'Stock List',
                    props: false,
                    component: () => import('../components/stock/StockIndex.vue')
                }
            ]
        },
        {
            path: '/area',
            name: 'Area Main Component',
            props: true,
            component: () => import('../components/area/AreaMainComponent.vue'),
            children: [
                {
                    path: '/areas',
                    name: 'Area List',
                    props: false,
                    component: () => import('../components/area/AreaIndex.vue')
                },
                {
                    path: '/add-area',
                    name: 'Add Map',
                    props: true,
                    component: () => import('../components/area/form/AddMap.vue')
                },
                {
                    path: '/add-area/:id',
                    name: 'Edit Map',
                    props: true,
                    component: () => import('../components/area/form/AddMap.vue')
                }
            ]
        },
        {
            path: '/attendances',
            name: 'Attendance Main Component',
            props: true,
            component: () => import('../components/attendance/AttendanceMainComponent.vue'),
            children: [
                {
                    path: '/attendance',
                    name: 'Attendance List',
                    props: false,
                    component: () => import('../components/attendance/AttendanceIndex.vue')
                }
            ]
        },
        {
            path: '/deploy',
            name: 'Deploy Main Component',
            props: true,
            component: () => import('../components/deploy/DeployMainComponent.vue'),
            children: [
                {
                    path: '/deploy',
                    name: 'Deploy List',
                    props: false,
                    component: () => import('../components/deploy/DeployIndex.vue')
                }
            ]
        },
        {
            path: '/qrcode',
            name: 'QrCode Main Component',
            props: true,
            component: () => import('../components/qrcode/QrcodeMainComponent.vue'),
            children: [
                {
                    path: '/codes',
                    name: 'Qrcode List',
                    props: false,
                    component: () => import('../components/qrcode/QrcodeIndex.vue')
                }
            ]
        },
        {
            path: '/deploy-employee',
            name: 'Deploy Employee Main Component',
            props: true,
            component: () => import('../components/deploy_employee/DeployEmployeeMainComponent.vue'),
            children: [
                {
                    path: '/deploy-employees',
                    name: 'Deploy Employee List',
                    props: false,
                    component: () => import('../components/deploy_employee/DeployEmployeeIndex.vue')
                }
            ]
        },
        {
            path : '/payroll',
            name: 'Payroll Main Component',
            props: true,
            component: () => import('../components/finance/FinanceMainComponent.vue'),
            children: [
                {
                    path: '/payrolls',
                    name: 'Payroll List',
                    props: true,
                    component: () => import('../components/finance/FinanceIndex.vue')
                },
                {
                    path: '/generate',
                    name: 'Generate Payroll',
                    props: true,
                    component: () => import('../components/finance/form/GeneratePayroll.vue')
                }
            ]
        },
        {
            path: '/harve',
            name: 'Harvest Main Component',
            props: true,
            component: () => import('../components/harvest/HarvestMainComponent.vue'),
            children: [
                {
                    path: '/harvest',
                    name: 'Harvest List',
                    props: true,
                    component: () => import('../components/harvest/HarvestIndex.vue')
                }
            ]
        },
        {
            path: '/team',
            name: 'Team Main Component',
            props: true,
            component: () => import('../components/team/TeamMainComponent.vue'),
            children: [
                {
                    path: '/teams',
                    name: 'Teams List',
                    component: () => import('../components/team/TeamIndex.vue'),
                    props: true,
                }
            ]
        },
        {
            path: '/task',
            name: 'Task Main Component',
            props: true,
            component: () => import('../components/task/TaskMainComponent.vue'),
            children: [
                {
                    path: '/tasks',
                    name: 'Task List',
                    component: () => import('../components/task/TaskIndex.vue'),
                    props: true,
                }
            ]
        },
        {
            path: '/operation',
            name: 'Daily Operation Main Component',
            props: true,
            component: () => import('../components/daily_operation/OperaitonMainComponent.vue'),
            children: [
                {
                    path: '/operations',
                    name: 'Daily Operation List',
                    component: () => import('../components/daily_operation/OperationIndex.vue'),
                    props: true,
                }
            ]
        },
        {
            path: '/half-month',
            name: 'Half Month Main Component',
            props: true,
            component: () => import('../components/half_month/HalfMonthMainComponent.vue'),
            children: [
                {
                    path: '/reports',
                    name: 'Half Month List',
                    component: () => import('../components/half_month/HalfMonthIndex.vue'),
                    props: true,
                },
                {
                    path: '/hgenerate',
                    name: 'Generate Half Month',
                    component: () => import('../components/half_month/form/HalfMonthForm.vue'),
                    props: true,
                },
                {
                    path: '/rdetails/:id',
                    name: 'Report Details',
                    props: true,
                    component: () => import('../components/half_month/partials/ViewReport.vue')
                }
            ]
        },
        {
            path: '/overtime',
            name: 'Overtime Main Component',
            props: true,
            component: () => import('../components/overtime/OvertimeMainComponent.vue'),
            children: [
                {
                    path: '/overtime-list',
                    name: 'Overtime List',
                    component: () => import('../components/overtime/OvertimeIndex.vue'),
                    props: true,
                }
            ]
        },
        {
            path: '/banana',
            name: 'Banana Main Component',
            props: true,
            component: () => import('../components/banana/BananaMainComponent.vue'),
            children: [
                {
                    path: '/yield-report',
                    name: 'Banana List',
                    component: () => import('../components/banana/BananaIndex.vue'),
                    props: true,
                }
            ]
        },
    ]
})
