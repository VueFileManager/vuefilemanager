const routesFile = [
    {
        name: 'Platform',
        path: '/platform',
        component: () => import(/* webpackChunkName: "chunks/platform" */ '../views/Platform'),
        children: [
            {
                name: 'Files',
                path: '/platform/files/:id?',
                component: () => import(/* webpackChunkName: "chunks/files" */ '../views/FileView/Files'),
                meta: {
                    requiresAuth: true,
                },
            },
            {
                name: 'RecentUploads',
                path: '/platform/recent-uploads',
                component: () =>
                    import(/* webpackChunkName: "chunks/recent-uploads" */ '../views/FileView/RecentUploads'),
                meta: {
                    requiresAuth: true,
                },
            },
            {
                name: 'MySharedItems',
                path: '/platform/my-shared-items',
                component: () =>
                    import(/* webpackChunkName: "chunks/my-shared-items" */ '../views/FileView/MySharedItems'),
                meta: {
                    requiresAuth: true,
                },
            },
            {
                name: 'Trash',
                path: '/platform/trash/:id?',
                component: () => import(/* webpackChunkName: "chunks/trash" */ '../views/FileView/Trash'),
                meta: {
                    requiresAuth: true,
                },
            },
        ],
    },
]

export default routesFile
