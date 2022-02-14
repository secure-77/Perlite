import json

f = open("perlite\Demo\metadata.json", "r")
filecontent = f.read()

metaJson = json.loads(filecontent)

notes = []
edges = []

for id, mdFiles in enumerate(metaJson):
    notes.append(id)
    notes[id] = {'id' : id , 'label' : mdFiles["fileName"]}

print(notes)

targetId = -1
srcId = -1

for mdFiles in metaJson:
    if "links" in mdFiles:
        for linkNames in mdFiles["links"]:
            source = mdFiles["fileName"]
            target = linkNames["link"]   

            for element in notes:
                elementLabel = element.get('label')
                if(elementLabel == target):
                    targetId = element.get('id')

                if(elementLabel == source):
                    srcId = element.get('id')

                if(targetId != -1 and srcId != -1):
                    edges.append({'from' : srcId , 'to' : targetId})
                    targetId = -1
                    srcId = -1

            
print(edges)

