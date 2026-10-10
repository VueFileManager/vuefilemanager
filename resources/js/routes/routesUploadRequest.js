const routesShared = [
    {
        name: 'Request',
        path: '/request',
        component: () => import(/* webpackChunkName: "chunks/request" */ '../views/UploadRequest.vue'),
        meta: {
            requiresAuth: false,
        },
        children: [
            {
                name: 'RequestUpload',
                path: '/request/:token/upload/:id?',
                component: () =>
                    import(/* webpackChunkName: "chunks/request-upload" */ '../views/FileView/UploadRequestFiles.vue'),
                meta: {
                    requiresAuth: false,
                },
            },
        ],
    },
]

export default routesShared
