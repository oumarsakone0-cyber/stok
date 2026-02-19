// Statistiques globales clients
export const getStatsGlobal = (params = {}) => {
  return api.get('api_clients.php?action=rapport_global', { params });
};

// Crédits en cours clients
export const getCreditsData = (params = {}) => {
  return api.get('api_clients.php?action=credits_en_cours', { params });
};
// ==================== CLIENTS ====================
// Liste des clients
export const getClients = () => {
  // Le backend récupère id_entreprise et role via le token
  return api.get('api_clients.php?action=list_clients');
};

// Détail d'un client
export const getClient = (id_client) => {
  return api.get('api_clients.php?action=get_client', { params: { id_client } });
};

// Ajouter un client
export const addClient = (payload) => {
  // Ne pas envoyer id_entreprise ni create_by, le backend les récupère via le token
  const { id_entreprise, create_by, ...rest } = payload;
  return api.post('api_clients.php?action=add_client', rest);
};

// Modifier un client
export const updateClient = (payload) => {
  // Ne pas envoyer id_entreprise ni create_by, le backend les récupère via le token
  const { id_entreprise, create_by, ...rest } = payload;
  return api.post('api_clients.php?action=update_client', rest);
};

// Supprimer un client
export const deleteClient = (id_client) => {
  return api.post('api_clients.php?action=delete_client', { id_client });
};
import axios from 'axios';

// Création d'une instance Axios pour toutes les requêtes API
const api = axios.create({
  baseURL: 'https://www.aliadjame.com/api',
  timeout: 30000,
  headers: {
    'Content-Type': 'application/json',
  },
});

// Intercepteur pour ajouter le token d'authentification à chaque requête
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('token');
    if (token) {
      config.headers['Authorization'] = `Bearer ${token}`;
    }
    return config;
  },
  (error) => Promise.reject(error)
);

// Intercepteur pour gérer les erreurs globalement
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response) {
      if (error.response.status === 401) {
        // Déconnexion automatique si le token n'est plus valide
        localStorage.removeItem('token');
        // window.location.href = '/login'; // Décommentez pour rediriger
        console.warn('Session expirée ou non autorisée');
      }
    }
    return Promise.reject(error);
  }
);

// Méthodes utilitaires pour les requêtes courantes
export const apiGet = (url, config = {}) => api.get(url, config);
export const apiPost = (url, data, config = {}) => api.post(url, data, config);
export const apiPut = (url, data, config = {}) => api.put(url, data, config);
export const apiDelete = (url, config = {}) => api.delete(url, config);

// Base URL de l'API (évite de la mettre en dur dans les vues)
export const getApiBaseUrl = () => api.defaults.baseURL;

// Authentification (api_auth.php)
// Connexion : POST api_auth.php?action=login
export const authLogin = (email, password) => {
  return api.post('api_auth.php?action=login', { email, password });
};

// Inscription : POST api_auth.php?action=register2
export const authRegister = (payload) => {
  return api.post('api_auth.php?action=register2', payload);
};

export default api;
