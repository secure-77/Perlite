import json
import hashlib

def remove_extension(path):
    return path[:-3]

def check_array(node_path):
    return True

json_metadata_file = "../Demo/metadata.json"
metadata_temp_file = "/tmp/metadata.temp"
metadata_temp_file_sum = "/tmp/metadata.md5"

with open(json_metadata_file, "r") as file:
    json_data = file.read()

if json_data is None:
    exit()

json_obj = json.loads(json_data)
if not isinstance(json_obj, list):
    exit()

graph_nodes = []
graph_edges = []

current_node = -1
node_id = 0

# create nodes
for node in json_obj:
    node_path = remove_extension(node['relativePath'])

    if check_array(node_path):
        graph_nodes.append({'id': node_id, 'label': node['fileName'], 'title': node_path})
        node_id += 1

target_id = -1
source_id = -1

for node in json_obj:
    node_path = remove_extension(node['relativePath'])

    if check_array(node_path):
        if 'links' in node:
            for link in node['links']:
                source = ""
                target = ""

                if 'relativePath' in node:
                    temp_path = remove_extension(node['relativePath'])
                    if check_array(temp_path):
                        source = temp_path

                if 'relativePath' in link:
                    temp_path = remove_extension(link['relativePath'])
                    if check_array(temp_path):
                        target = temp_path

                if source and target:
                    for element in graph_nodes:
                        element_title = element['title']
                        if element_title == target:
                            target_id = element['id']
                        if element_title == source:
                            source_id = element['id']

                        edge_exists = any(
                            edge['from'] == source_id and edge['to'] == target_id or
                            edge['to'] == source_id and edge['from'] == target_id
                            for edge in graph_edges
                        )

                        if target_id != -1 and source_id != -1:
                            if not edge_exists:
                                graph_edges.append({'from': source_id, 'to': target_id})
                            target_id = -1
                            source_id = -1

my_graph_nodes = json.dumps(graph_nodes, separators=(',', ':'), ensure_ascii=False)
my_graph_edges = json.dumps(graph_edges, separators=(',', ':'), ensure_ascii=False)

graph_html = f'<div id="allGraphNodes" style="display: none">{my_graph_nodes}</div><div id="allGraphEdges" style="display: none">{my_graph_edges}</div>'

with open(metadata_temp_file, "w") as file:
    file.write(graph_html)

with open(metadata_temp_file_sum, "w") as file_handler:
        md5_filesum = hashlib.md5()
        with open(json_metadata_file, "rb") as json_file:
            while chunk := json_file.read(8192):
                md5_filesum.update(chunk)
        file_handler.write(md5_filesum.hexdigest())