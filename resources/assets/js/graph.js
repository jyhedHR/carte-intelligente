/**
 * Impresarios & Contracts - Network Graph Visualization
 * Interactive visualization of relationships between artists, impresarios, and events
 */

let network = null;
let graphData = null;

// Mock Data - Relations between Artists, Impresarios, and Events
const mockGraphData = {
    impresarios: [
        { id: 'imp-1', name: 'Samir Khalil', company: 'Art Management Tunis', dept: 'music', status: 'verified' },
        { id: 'imp-2', name: 'Leila Mansour', company: 'Desert Dreams', dept: 'dance', status: 'verified' },
        { id: 'imp-3', name: 'Mohamed Saïd', company: 'Sfax Entertainment', dept: 'music', status: 'pending' },
        { id: 'imp-4', name: 'Fatima Zouari', company: 'Étoile du Nord', dept: 'dance', status: 'verified' },
        { id: 'imp-5', name: 'Ahmed Bennour', company: 'Sound Horizon', dept: 'music', status: 'suspended' }
    ],
    artists: [
        { id: 'art-1', name: 'Amine Tagri', dept: 'music' },
        { id: 'art-2', name: 'Salma Bent', dept: 'dance' },
        { id: 'art-3', name: 'Naoual Slimani', dept: 'music' },
        { id: 'art-4', name: 'Zaineb Mansouri', dept: 'dance' },
        { id: 'art-5', name: 'Karim El Gharbi', dept: 'music' },
        { id: 'art-6', name: 'Samira Bouali', dept: 'dance' },
        { id: 'art-7', name: 'Tariq Harri', dept: 'music' },
        { id: 'art-8', name: 'Jasmine Chakra', dept: 'dance' }
    ],
    events: [
        { id: 'evt-1', name: 'Festival Jazz 2026', date: '2026-06-15', dept: 'music' },
        { id: 'evt-2', name: 'Dance Night Tunis', date: '2026-05-20', dept: 'dance' },
        { id: 'evt-3', name: 'Classical Night', date: '2026-07-10', dept: 'music' },
        { id: 'evt-4', name: 'Contemporary Dance Fest', date: '2026-08-05', dept: 'dance' }
    ],
    relationships: [
        // Impresario -> Artists (represents)
        { from: 'imp-1', to: 'art-1', type: 'represents' },
        { from: 'imp-1', to: 'art-3', type: 'represents' },
        { from: 'imp-1', to: 'art-5', type: 'represents' },
        { from: 'imp-2', to: 'art-2', type: 'represents' },
        { from: 'imp-2', to: 'art-4', type: 'represents' },
        { from: 'imp-2', to: 'art-6', type: 'represents' },
        { from: 'imp-3', to: 'art-1', type: 'represents' }, // Multiple representation - conflict
        { from: 'imp-3', to: 'art-7', type: 'represents' },
        { from: 'imp-4', to: 'art-2', type: 'represents' },
        { from: 'imp-4', to: 'art-8', type: 'represents' },
        { from: 'imp-5', to: 'art-3', type: 'represents' }, // Suspended impresario - conflict

        // Artists -> Events (performs)
        { from: 'art-1', to: 'evt-1', type: 'performs' },
        { from: 'art-3', to: 'evt-3', type: 'performs' },
        { from: 'art-5', to: 'evt-1', type: 'performs' },
        { from: 'art-2', to: 'evt-2', type: 'performs' },
        { from: 'art-4', to: 'evt-2', type: 'performs' },
        { from: 'art-6', to: 'evt-4', type: 'performs' },
        { from: 'art-7', to: 'evt-1', type: 'performs' },
        { from: 'art-8', to: 'evt-4', type: 'performs' }
    ]
};

// Generate graph visualization
window.generateGraph = function() {


    const container = document.getElementById('networkGraph');
    const graphContainer = document.getElementById('graphContainer');
    const graphInitial = document.getElementById('graphInitial');
    const generateBtn = document.getElementById('generateGraphBtn');
    const resetBtn = document.getElementById('resetGraphBtn');

    if (!container) {
        console.error('[v0] Graph container not found');
        return;
    }

    // Show graph container, hide initial state
    graphContainer.style.display = 'block';
    graphInitial.style.display = 'none';
    generateBtn.style.display = 'none';
    resetBtn.style.display = 'inline-block';

    // Build nodes and edges
    const nodes = new vis.DataSet();
    const edges = new vis.DataSet();

    // Add Impresarios as nodes
    mockGraphData.impresarios.forEach(imp => {
        const color = imp.status === 'verified' ? 'var(--gold)' :
                     imp.status === 'pending' ? '#FFA500' : 'var(--red)';

        nodes.add({
            id: imp.id,
            label: imp.name,
            title: `<strong>${imp.name}</strong><br/>Entreprise: ${imp.company}<br/>Statut: ${imp.status}`,
            color: color,
            shape: 'dot',
            size: 30,
            font: { size: 12, color: '#fff', face: 'Tahoma' },
            physics: true
        });
    });

    // Add Artists as nodes
    mockGraphData.artists.forEach(art => {
        nodes.add({
            id: art.id,
            label: art.name,
            title: `<strong>${art.name}</strong><br/>Département: ${art.dept}`,
            color: 'var(--blue)',
            shape: 'dot',
            size: 25,
            font: { size: 11, color: '#fff' },
            physics: true
        });
    });

    // Add Events as nodes
    mockGraphData.events.forEach(evt => {
        nodes.add({
            id: evt.id,
            label: evt.name,
            title: `<strong>${evt.name}</strong><br/>Date: ${evt.date}<br/>Département: ${evt.dept}`,
            color: 'var(--teal)',
            shape: 'box',
            size: 20,
            font: { size: 11, color: '#fff' },
            physics: true
        });
    });

    // Detect conflicts and add edges
    const conflicts = detectConflicts();

    mockGraphData.relationships.forEach(rel => {
        const isConflict = conflicts.some(c =>
            (c.type === 'multiple_representation' && c.artist === rel.to && c.impresarios.includes(rel.from)) ||
            (c.type === 'suspended_contract' && c.impresario === rel.from)
        );

        edges.add({
            from: rel.from,
            to: rel.to,
            label: rel.type === 'represents' ? 'Représente' : 'Perform',
            color: isConflict ? 'var(--red)' : '#888',
            width: isConflict ? 3 : 1,
            arrows: 'to',
            smooth: { type: 'continuous' },
            dashes: isConflict ? [5, 5] : false,
            font: { size: 10 }
        });
    });

    // Network options
    const options = {
        physics: {
            enabled: true,
            forceAtlas2Based: {
                gravitationalConstant: -26,
                centralGravity: 0.005,
                springLength: 230,
                springConstant: 0.08
            },
            maxVelocity: 50,
            solver: 'forceAtlas2Based',
            timestep: 0.35,
            stabilization: {
                iterations: 150
            }
        },
        interaction: {
            hover: true,
            navigationButtons: true,
            keyboard: true,
            zoomView: true,
            dragView: true
        },
        nodes: {
            borderWidth: 2,
            margin: 10
        },
        edges: {
            smooth: {
                type: 'continuous',
                forceDirection: 'none'
            },
            font: {
                size: 11,
                color: 'var(--text)'
            }
        }
    };

    // Create network
    const data = { nodes: nodes, edges: edges };
    network = new vis.Network(container, data, options);

    // Update statistics
    const nodeImpCount = mockGraphData.impresarios.length;
    const nodeArtCount = mockGraphData.artists.length;
    const nodeEventCount = mockGraphData.events.length;
    const edgeCount = mockGraphData.relationships.length;
    const conflictCount = conflicts.length;

    document.getElementById('nodeImprCount').textContent = nodeImpCount;
    document.getElementById('nodeArtCount').textContent = nodeArtCount;
    document.getElementById('nodeEventCount').textContent = nodeEventCount;
    document.getElementById('edgeCount').textContent = edgeCount;
    document.getElementById('conflictCount').textContent = conflictCount;

    console.log('[v0] Graph generated successfully');
    console.log('[v0] Conflicts detected:', conflictCount);

    // Node click handler
    network.on('click', function(params) {
        if (params.nodes.length > 0) {
            const nodeId = params.nodes[0];
            handleNodeClick(nodeId);
        }
    });

    // Physics stabilization callback
    network.once('stabilizationIterationsDone', function() {
        console.log('[v0] Physics simulation complete');
        network.setOptions({ physics: false });
    });
}

// Detect conflicts in the graph
window.detectConflicts = function() {
    const conflicts = [];

    // Check for artists with multiple impresarios (without exclusive contracts)
    const artistImpresarioMap = {};
    mockGraphData.relationships.forEach(rel => {
        if (rel.type === 'represents') {
            if (!artistImpresarioMap[rel.to]) {
                artistImpresarioMap[rel.to] = [];
            }
            artistImpresarioMap[rel.to].push(rel.from);
        }
    });

    for (const [artist, impresarios] of Object.entries(artistImpresarioMap)) {
        if (impresarios.length > 1) {
            conflicts.push({
                type: 'multiple_representation',
                artist: artist,
                impresarios: impresarios,
                description: `L'artiste ${artist} est représenté par ${impresarios.length} imprésarios`
            });
        }
    }

    // Check for suspended impresarios with active contracts
    mockGraphData.impresarios.forEach(imp => {
        if (imp.status === 'suspended') {
            const hasContracts = mockGraphData.relationships.some(rel => rel.from === imp.id && rel.type === 'represents');
            if (hasContracts) {
                conflicts.push({
                    type: 'suspended_contract',
                    impresario: imp.id,
                    impresarioName: imp.name,
                    description: `L'imprésario suspendu ${imp.name} a toujours des contrats actifs`
                });
            }
        }
    });

    return conflicts;
}

// Handle node click
window.handleNodeClick = function(nodeId) {
    console.log('[v0] Node clicked:', nodeId);

    let nodeInfo = '';
    let type = '';

    // Determine node type and get details
    const impresario = mockGraphData.impresarios.find(i => i.id === nodeId);
    if (impresario) {
        type = 'Imprésario';
        const artists = mockGraphData.relationships
            .filter(r => r.from === nodeId && r.type === 'represents')
            .map(r => mockGraphData.artists.find(a => a.id === r.to)?.name)
            .filter(Boolean);

        nodeInfo = `
            <strong>${impresario.name}</strong><br/>
            Entreprise: ${impresario.company}<br/>
            Département: ${impresario.dept}<br/>
            Statut: ${impresario.status}<br/>
            Artistes représentés: ${artists.length}<br/>
            ${artists.slice(0, 3).map(a => `• ${a}`).join('<br/>')}
            ${artists.length > 3 ? `<br/>... et ${artists.length - 3} autres` : ''}
        `;
    }

    const artist = mockGraphData.artists.find(a => a.id === nodeId);
    if (artist) {
        type = 'Artiste';
        const impresarios = mockGraphData.relationships
            .filter(r => r.to === nodeId && r.type === 'represents')
            .map(r => mockGraphData.impresarios.find(i => i.id === r.from)?.name)
            .filter(Boolean);

        const events = mockGraphData.relationships
            .filter(r => r.from === nodeId && r.type === 'performs')
            .map(r => mockGraphData.events.find(e => e.id === r.to)?.name)
            .filter(Boolean);

        nodeInfo = `
            <strong>${artist.name}</strong><br/>
            Département: ${artist.dept}<br/>
            Représentants: ${impresarios.join(', ') || 'Aucun'}<br/>
            Événements: ${events.length}<br/>
            ${events.slice(0, 3).map(e => `• ${e}`).join('<br/>')}
            ${events.length > 3 ? `<br/>... et ${events.length - 3} autres` : ''}
        `;
    }

    const event = mockGraphData.events.find(e => e.id === nodeId);
    if (event) {
        type = 'Événement';
        const performers = mockGraphData.relationships
            .filter(r => r.to === nodeId && r.type === 'performs')
            .map(r => mockGraphData.artists.find(a => a.id === r.from)?.name)
            .filter(Boolean);

        nodeInfo = `
            <strong>${event.name}</strong><br/>
            Date: ${event.date}<br/>
            Département: ${event.dept}<br/>
            Artistes: ${performers.length}<br/>
            ${performers.slice(0, 3).map(p => `• ${p}`).join('<br/>')}
            ${performers.length > 3 ? `<br/>... et ${performers.length - 3} autres` : ''}
        `;
    }

    if (nodeInfo) {
        showNodeModal(type, nodeInfo);
    }
}

// Show node details modal
window.showNodeModal = function(type, info) {
    // Use browser tooltip for simplicity
    console.log(`[v0] ${type} Details:\n${info}`);
    alert(`${type}\n\n${info.replace(/<br\/>/g, '\n').replace(/<strong>/g, '').replace(/<\/strong>/g, '')}`);
}

// Reset graph
window.resetGraph = function() {
    console.log('[v0] Resetting graph...');

    const graphContainer = document.getElementById('graphContainer');
    const graphInitial = document.getElementById('graphInitial');
    const generateBtn = document.getElementById('generateGraphBtn');
    const resetBtn = document.getElementById('resetGraphBtn');

    // Hide graph, show initial state
    graphContainer.style.display = 'none';
    graphInitial.style.display = 'flex';
    generateBtn.style.display = 'inline-block';
    resetBtn.style.display = 'none';

    // Destroy network
    if (network) {
        network.destroy();
        network = null;
    }
}
