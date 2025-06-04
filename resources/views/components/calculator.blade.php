<!-- Calculator Component -->
<div id="calculator-content" class="tool-content">
    <div class="calculator">
        <div class="calc-display">
            <input type="text" id="calc-display" readonly>
        </div>
        <div class="calc-buttons">
            <button onclick="clearCalc()" class="special">C</button>
            <button onclick="appendToCalc('(')" class="operator">(</button>
            <button onclick="appendToCalc(')')" class="operator">)</button>
            <button onclick="appendToCalc('/')" class="operator">÷</button>
            
            <button onclick="appendToCalc('7')">7</button>
            <button onclick="appendToCalc('8')">8</button>
            <button onclick="appendToCalc('9')">9</button>
            <button onclick="appendToCalc('*')" class="operator">×</button>
            
            <button onclick="appendToCalc('4')">4</button>
            <button onclick="appendToCalc('5')">5</button>
            <button onclick="appendToCalc('6')">6</button>
            <button onclick="appendToCalc('-')" class="operator">−</button>
            
            <button onclick="appendToCalc('1')">1</button>
            <button onclick="appendToCalc('2')">2</button>
            <button onclick="appendToCalc('3')">3</button>
            <button onclick="appendToCalc('+')" class="operator">+</button>
            
            <button onclick="appendToCalc('0')" class="span-2">0</button>
            <button onclick="appendToCalc('.')">.</button>
            <button onclick="calculateResult()" class="operator">=</button>
        </div>
    </div>
</div>

<style>
.calculator {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    max-width: 400px;
    margin: 0 auto;
}

.calc-display {
    margin-bottom: 1.5rem;
}

#calc-display {
    width: 100%;
    padding: 1rem;
    font-size: 2rem;
    text-align: right;
    border: none;
    background: #f8f9fa;
    border-radius: 8px;
    color: #2c3e50;
}

.calc-buttons {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 0.75rem;
}

.calc-buttons button {
    padding: 1rem;
    font-size: 1.25rem;
    border: none;
    background: white;
    border-radius: 8px;
    color: #2c3e50;
    cursor: pointer;
    transition: all 0.2s;
    border: 1px solid #eee;
}

.calc-buttons button:hover {
    background: #f8f9fa;
}

.calc-buttons button:active {
    transform: scale(0.95);
}

.calc-buttons .operator {
    background: #3498db;
    color: white;
    border: none;
}

.calc-buttons .operator:hover {
    background: #2980b9;
}

.calc-buttons .special {
    background: #e74c3c;
    color: white;
    border: none;
}

.calc-buttons .special:hover {
    background: #c0392b;
}

.calc-buttons .span-2 {
    grid-column: span 2;
}
</style> 