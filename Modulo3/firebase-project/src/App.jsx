
import { LoginComponent } from './views/login/LoginComponents'
import { Products } from './views/products/Products'
import { BrowserRouter as Router, Routes, Route} from 'react-router'
function App() {
  //Genstion de rutas
  return (
    <Router>
      <Routes>
        <Route path="/" element={<LoginComponent />} />
        <Route path="/login" element={<LoginComponent />} />
        <Route 
          path="/products" 
          element={
              <Products/>
          } 
        />
      </Routes>
    </Router>
  )
}

export default App
