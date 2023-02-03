from collections import defaultdict

n, m = map(int, input().split())

edges = []
parent = [i for i in range(n)]

for _ in range(m):
	a, b, c = map(int, input().split())
	edges.append((a-1, b-1, c))

def find(i):
	while i != parent[parent[i]]:
		parent[i] = parent[parent[i]]
		i = parent[i]
	return i

def union(x, y):
	p_x = find(x)
	p_y = find(y)
	parent[p_y] = p_x

def is_connected(x, y):
	p_x = find(x)
	p_y = find(y)
	return p_x == p_y

# build MST
tree = defaultdict(list)

edges.sort(key = lambda x:x[2])

for a, b, c in edges:
	if not is_connected(a, b):
		union(a, b)
		tree[a].append((b, c))
		tree[b].append((a, c))

ans = [0]*(2*m)

# Run DFS to count the number of times an edge is used
# as weights of all edges is different, hence each weight maps to a particular children
def dfs(src, p = -1):
	total = 1
	for v, c in tree[src]:
		if v != p:
			children = dfs(v, src)

			# children => nodes right to edge, n - children => nodes left to edge
			ans[c] += (n - children)*children

			total += children
	return total

dfs(0)

print(ans)

res = 0
for i in range(len(ans)):
	res += ans[i] * (1 << i)

print(res)

print(str(bin(res))[2:])
