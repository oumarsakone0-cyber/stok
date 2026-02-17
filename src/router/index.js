import { createRouter, createWebHistory } from "vue-router"
import { useAuthStore } from "../stores/authStore"

const routes = [
  {
    path: "/login",
    name: "login",
    component: () => import("../views/app_auth/Login.vue"),
    meta: { requiresAuth: false, public: true }
  },
  {
    path: "/",
    name: "home",
    component: () => import("../views/Home.vue"),
    meta: { requiresAuth: true }
  },
  {
    path: "/magasins",
    name: "magasins",
    component: () => import("../views/magasins/MagasinsList.vue"),
    meta: { 
      requiresAuth: true,
      requiresAccess: 'magasins'
    }
  },
  {
    path: "/magasins/:id",
    name: "magasin-detail",
    component: () => import("../views/magasins/MagasinDetail.vue"),
    meta: { 
      requiresAuth: true,
      requiresAccess: 'magasin',
      checkMagasinId: true
    }
  },
  {
    path: "/magasins/:id/produits",
    name: "magasin-produits",
    component: () => import("../views/magasins/MagasinProduits.vue"),
    meta: { 
      requiresAuth: true,
      requiresAccess: 'magasin',
      checkMagasinId: true
    }
  },
  {
    path: "/magasins/:id/stock",
    name: "magasin-stock",
    component: () => import("../views/magasins/MagasinStock.vue"),
    meta: { 
      requiresAuth: true,
      requiresAccess: 'magasin',
      checkMagasinId: true
    }
  },
  {
    path: "/magasins/:id/ventes",
    name: "magasin-ventes",
    component: () => import("../views/magasins/MagasinVentes.vue"),
    meta: { 
      requiresAuth: true,
      requiresAccess: 'magasin',
      checkMagasinId: true
    }
  },
  {
    path: "/entrepots",
    name: "entrepots",
    component: () => import("../views/entrepots/Entrepots_list.vue"),
    meta: { 
      requiresAuth: true,
      requiresAccess: 'entrepots'
    }
  },
  {
    path: "/entrepots/:id",
    name: "entrepots-detail",
    component: () => import("../views/entrepots/Entrepots_detail.vue"),
    meta: { 
      requiresAuth: true,
      requiresAccess: 'entrepot',
      checkEntrepotId: true
    }
  },
  {
    path: "/clients",
    name: "clients",
    component: () => import("../views/clients/client_list.vue"),
    meta: { 
      requiresAuth: true,
      requiresAccess: 'clients'
    }
  },
  {
    path: "/clients/:id",
    name: "clients-detail",
    component: () => import("../views/clients/client_detail.vue"),
    meta: { 
      requiresAuth: true,
      requiresAccess: 'clients'
    }
  },
  {
    path: "/fournisseurs",
    name: "fournisseurs",
    component: () => import("../views/fournisseurs/fournisseurs_list.vue"),
    meta: { 
      requiresAuth: true,
      requiresAccess: 'fournisseurs'
    }
  },
  {
    path: "/fournisseurs/:id",
    name: "fournisseurs-detail",
    component: () => import("../views/fournisseurs/fournisseur_detail.vue"),
    meta: { 
      requiresAuth: true,
      requiresAccess: 'fournisseurs'
    }
  },
  {
    path: "/utilisateurs",
    name: "utilisateurs",
    component: () => import("../views/Users.vue"),
    meta: { 
      requiresAuth: true,
      requiresAccess: 'users',
      requiresAdmin: true
    }
  },
  {
    path: "/departement/senasip",
    name: "senasip",
    component: () => import("../views/senasip/Senasipdashboard.vue"),
    meta: { requiresAuth: true }
  },
  // Page 403 - Accès refusé
  {
    path: "/403",
    name: "forbidden",
    component: () => import("../views/Forbidden.vue"),
    meta: { requiresAuth: false, public: true }
  },
  // Page 404 - Not Found
  {
    path: "/:pathMatch(.*)*",
    name: "not-found",
    component: () => import("../views/Notfound.vue"),
    meta: { requiresAuth: false, public: true }
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

// Guard de navigation global
router.beforeEach((to, from, next) => {
  const authStore = useAuthStore()
  
  // Restaurer la session si elle existe
  if (!authStore.isLoggedIn) {
    authStore.restoreSession()
  }
  
  // Pages publiques
  if (to.meta.public) {
    // Si déjà connecté et va vers login, rediriger vers home
    if (to.name === 'login' && authStore.isLoggedIn) {
      return next({ name: 'home' })
    }
    return next()
  }
  
  // Vérifier l'authentification
  if (to.meta.requiresAuth && !authStore.isLoggedIn) {
    return next({ 
      name: 'login',
      query: { redirect: to.fullPath }
    })
  }
  
  // Vérifier les accès spécifiques
  if (to.meta.requiresAccess) {
    const accessType = to.meta.requiresAccess
    
    switch (accessType) {
      case 'magasins':
        if (!authStore.hasAccessToMagasins) {
          return next({ name: 'forbidden' })
        }
        break
        
      case 'magasin':
        if (to.meta.checkMagasinId && to.params.id) {
          if (!authStore.hasAccessToMagasin(to.params.id)) {
            return next({ name: 'forbidden' })
          }
        }
        break
        
      case 'entrepots':
        if (!authStore.hasAccessToEntrepots) {
          return next({ name: 'forbidden' })
        }
        break
        
      case 'entrepot':
        if (to.meta.checkEntrepotId && to.params.id) {
          if (!authStore.hasAccessToEntrepot(to.params.id)) {
            return next({ name: 'forbidden' })
          }
        }
        break
        
      case 'clients':
        if (!authStore.hasAccessToClients) {
          return next({ name: 'forbidden' })
        }
        break
        
      case 'fournisseurs':
        if (!authStore.hasAccessToFournisseurs) {
          return next({ name: 'forbidden' })
        }
        break
        
      case 'users':
        if (!authStore.hasAccessToUsers) {
          return next({ name: 'forbidden' })
        }
        break
    }
  }
  
  // Vérifier si admin requis
  if (to.meta.requiresAdmin && !authStore.isAdmin) {
    return next({ name: 'forbidden' })
  }
  
  next()
})

export default router