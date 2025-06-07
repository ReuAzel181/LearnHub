<!-- URL: /dictionary -->
<div id="dictionary-content" class="tool-content">
    <div class="dictionary-container">
        <div class="search-box">
            <div class="search-input-wrapper">
                <i class="fas fa-search"></i>
                <input type="text" id="word-search" placeholder="Type a word to search..." onkeypress="if(event.key === 'Enter') searchWord()">
            </div>
            <button class="search-button" onclick="searchWord()">Search</button>
        </div>
        <div id="definition-result"></div>
    </div>
</div>

<script>
function initializeDictionary() {
    // Clear previous search
    document.getElementById('word-search').value = '';
    document.getElementById('definition-result').innerHTML = '';
    
    // Focus on search input
    document.getElementById('word-search').focus();
}

async function searchWord() {
    const word = document.getElementById('word-search').value.trim();
    if (!word) return;
    
    const resultDiv = document.getElementById('definition-result');
    resultDiv.innerHTML = '<p>Searching...</p>';
    
    try {
        const response = await fetch(`https://api.dictionaryapi.dev/api/v2/entries/en/${word}`);
        const data = await response.json();
        
        if (response.ok) {
            displayDefinitions(data);
        } else {
            resultDiv.innerHTML = `<div class="no-results">No definitions found for "${word}"</div>`;
        }
    } catch (error) {
        resultDiv.innerHTML = '<div class="no-results">An error occurred while searching. Please try again.</div>';
    }
}

function displayDefinitions(data) {
    const resultDiv = document.getElementById('definition-result');
    resultDiv.innerHTML = '';
    
    data[0].meanings.forEach(meaning => {
        const meaningDiv = document.createElement('div');
        meaningDiv.className = 'meaning';
        
        meaningDiv.innerHTML = `
            <h4>${meaning.partOfSpeech}</h4>
            <ul>
                ${meaning.definitions.map(def => `<li>${def.definition}</li>`).join('')}
            </ul>
        `;
        
        resultDiv.appendChild(meaningDiv);
    });
}
</script>

<style>
.dictionary-container {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    max-width: 800px;
    margin: 0 auto;
}

.search-box {
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
}

.search-input-wrapper {
    flex: 1;
    position: relative;
}

.search-input-wrapper i {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #95a5a6;
}

#word-search {
    width: 100%;
    padding: 1rem 1rem 1rem 3rem;
    font-size: 1.1rem;
    border: 2px solid #eee;
    border-radius: 8px;
    transition: all 0.2s;
}

#word-search:focus {
    outline: none;
    border-color: #3498db;
}

.search-button {
    padding: 0 2rem;
    background: #3498db;
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.search-button:hover {
    background: #2980b9;
}

#definition-result {
    padding-top: 1rem;
}

.meaning {
    margin-bottom: 2rem;
}

.meaning h4 {
    color: #2c3e50;
    font-size: 1.2rem;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #eee;
}

.meaning ul {
    list-style: none;
    padding: 0;
}

.meaning li {
    margin-bottom: 1rem;
    padding-left: 1.5rem;
    position: relative;
    color: #34495e;
    line-height: 1.6;
}

.meaning li::before {
    content: "•";
    position: absolute;
    left: 0;
    color: #3498db;
}

.no-results {
    text-align: center;
    color: #7f8c8d;
    padding: 2rem;
}
</style> 