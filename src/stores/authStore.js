import { defineStore } from 'pinia'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: null,
    isAuthenticated: false
  }),

  getters: {
    // Vérifier si l'utilisateur est authentifié
    isLoggedIn: (state) => state.isAuthenticated && state.user !== null,
    
    // Obtenir les informations utilisateur
    currentUser: (state) => state.user,
    
    // Vérifier le rôle
    isAdmin: (state) => state.user?.role === 'admin',
    isManager: (state) => state.user?.role === 'manager',
    isVendeur: (state) => state.user?.role === 'vendeur',
    
    // Accès aux modules
    hasAccessToClients: (state) => {
      if (!state.user) return false
      return state.user.acces?.clients === true || state.user.role === 'admin'
    },
    
    hasAccessToFournisseurs: (state) => {
      if (!state.user) return false
      return state.user.acces?.fournisseurs === true || state.user.role === 'admin'
    },
    
    // Accès magasins
    hasAccessToMagasins: (state) => {
      if (!state.user) return false
      const acces = state.user.acces?.magasins
      return acces === 'all' || (Array.isArray(acces) && acces.length > 0) || state.user.role === 'admin'
    },
    
    hasAccessToMagasin: (state) => (magasinId) => {
      if (!state.user) return false
      if (state.user.role === 'admin') return true
      const acces = state.user.acces?.magasins
      if (acces === 'all') return true
      if (Array.isArray(acces)) {
        return acces.includes(parseInt(magasinId))
      }
      return false
    },
    
    // Accès entrepôts
    hasAccessToEntrepots: (state) => {
      if (!state.user) return false
      const acces = state.user.acces?.entrepots
      return acces === 'all' || (Array.isArray(acces) && acces.length > 0) || state.user.role === 'admin'
    },
    
    hasAccessToEntrepot: (state) => (entrepotId) => {
      if (!state.user) return false
      if (state.user.role === 'admin') return true
      const acces = state.user.acces?.entrepots
      if (acces === 'all') return true
      if (Array.isArray(acces)) {
        return acces.includes(parseInt(entrepotId))
      }
      return false
    },
    
    // Accès utilisateurs (admin uniquement)
    hasAccessToUsers: (state) => {
      if (!state.user) return false
      return state.user.role === 'admin'
    },
    
    // Liste des magasins accessibles
    accessibleMagasins: (state) => {
      if (!state.user) return []
      const acces = state.user.acces?.magasins
      if (acces === 'all') return 'all'
      if (Array.isArray(acces)) return acces
      return []
    },
    
    // Liste des entrepôts accessibles
    accessibleEntrepots: (state) => {
      if (!state.user) return []
      const acces = state.user.acces?.entrepots
      if (acces === 'all') return 'all'
      if (Array.isArray(acces)) return acces
      return []
    }
  },

  actions: {
    // Connexion
    async login(userData) {
      this.user = userData
      this.token = userData.token
      this.isAuthenticated = true
      
      // Sauvegarder dans localStorage
      localStorage.setItem('user', JSON.stringify(userData))
      localStorage.setItem('token', userData.token)
      localStorage.setItem('isAuthenticated', 'true')
    },
    
    // Déconnexion
    logout() {
      this.user = null
      this.token = null
      this.isAuthenticated = false
      
      // Nettoyer localStorage
      localStorage.removeItem('user')
      localStorage.removeItem('token')
      localStorage.removeItem('isAuthenticated')
    },
    
    // Restaurer la session depuis localStorage
    restoreSession() {
      const user = localStorage.getItem('user')
      const token = localStorage.getItem('token')
      const isAuthenticated = localStorage.getItem('isAuthenticated')
      
      if (user && token && isAuthenticated === 'true') {
        try {
          this.user = JSON.parse(user)
          this.token = token
          this.isAuthenticated = true
          return true
        } catch (error) {
          console.error('Erreur lors de la restauration de la session:', error)
          this.logout()
          return false
        }
      }
      return false
    },
    
    // Mettre à jour les informations utilisateur
    updateUser(userData) {
      this.user = { ...this.user, ...userData }
      localStorage.setItem('user', JSON.stringify(this.user))
    }
  }
})