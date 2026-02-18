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

// ——— Authentification (api_auth.php) ———
/** Connexion : POST api_auth.php?action=login */
export const authLogin = (email, password) =>
  api.post('api_auth.php?action=login', { email, password });

/** Inscription : POST api_auth.php?action=register2 */
export const authRegister = (payload) =>
  api.post('api_auth.php?action=register2', payload);

export default api;
