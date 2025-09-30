import { StrictMode } from 'react'
import "../node_modules/bootstrap/dist/css/bootstrap.min.css"
import "../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"
import { createRoot } from 'react-dom/client'
import MainLayout from './Layout/MainLayout'

createRoot(document.getElementById('root')).render(
  <>  
  <MainLayout/>
  </>
)
