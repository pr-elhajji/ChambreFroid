export const menuItems = [
    {
        id: 1,
        label: "Menu",
        isTitle: true
    },
    {
        id: 2,
        label: "Accueil ",
        icon: "bx-home-circle",
        link: "/"
    },
    {
        id: 3,
        label: "Tableau de bord",
        icon: "bx-tachometer",
        link: "/dashboard"
    },
    {
        id: 8,
        label: "Rapports",
        icon: "bx:bxs-widget",
        link: "/#"
    },
    {
        id: 9,
        label: "Configuration",
        icon: "bx:bxs-widget",
        link: "/#",
        subItems: [
            {
                id: 10,
                label: "Chambres",
                link: "javascript: void(0);",
                parentId: 9
            },
            {
                id: 11,
                label: "Lots",
                link: "javascript: void(0);",
                parentId: 9
            },
            {
                id: 12,
                label: "Variétés",
                link: "javascript: void(0);",
                parentId: 9
            },
            {
                id: 13,
                label: "Alerts",
                link: "javascript: void(0);",
                parentId: 9
            },
            {
                id: 13,
                label: "Cartes",
                link: "javascript: void(0);",
                parentId: 9
            }
        ]
    }
];
