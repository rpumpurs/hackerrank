void print(Node root, int space)
{
    int COUNT = 10;

    // Base case
    if (root == null)
        return;

    // Increase distance between levels
    space += COUNT;

    // Process right child first
    print(root.right, space);

    // Print current node after space
    // count
    System.out.print("\n");
    for (int i = COUNT; i < space; i++)
        System.out.print(" ");
    System.out.print(root.data + "\n");

    // Process left child
    print(root.left, space);
}
