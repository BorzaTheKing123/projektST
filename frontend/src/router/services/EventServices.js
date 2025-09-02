import axios from 'axios'

// Axios instanca za API klice
const apiClients = axios.create({
  baseURL: 'http://localhost:8000/api/',
  headers: {
    Accept: 'application/json',
    'Content-Type': 'application/json'
  }
})

// 🔐 Nastavi Authorization header, če je token v localStorage
export function loadAuthToken() {
  const savedToken = localStorage.getItem('auth_token')
  if (savedToken) {
    apiClients.defaults.headers.common['Authorization'] = `Bearer ${savedToken}`
  }
}

// 🔧 Pokliči ob zagonu aplikacije
loadAuthToken()

// 📦 API metode
const EventServices = {
  login(email, password) {
    return apiClients.post('/login', { email, password })
      .then(response => {
        const token = response.data.token
        localStorage.setItem('auth_token', token)
        loadAuthToken()
        return response
      })
  },

  getStranke() {
    return apiClients.get('/stranke')
  },

  logout() {
    return apiClients.post('/logout').then(() => {
      localStorage.removeItem('auth_token')
      delete apiClients.defaults.headers.common['Authorization']
    })
  },
  
  getRegister() {
    return apiClients.get('/register')
  },

  register(name, email, phone) {
    return apiClients.post('/register', {
      name,
      email,
      phone
    })
  },

  addStranka(data) {
    return apiClients.post('stranke/dodaj', data)
  },



  // Dodaj po potrebi: register, getStranka, dodajStranke ...
}

export default EventServices
export { apiClients }





