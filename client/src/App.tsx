import { HashRouter as Router, Routes, Route } from 'react-router-dom';
import Home from './pages/Home';
import PainelLojista from './pages/Painel';

function App() {
  return (
    <Router>
      <Routes>
        <Route path="/" element={<Home />} />
        <Route path="/painel" element={<PainelLojista />} />
      </Routes>
    </Router>
  );
}

export default App;