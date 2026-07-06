#let data = json(sys.inputs.data)

// ── Safe field helpers ──
#let institution = if "institution_name" in data { data.institution_name } else { "Bestlink College of the Philippines" }
#let file_folder  = if "file_folder" in data  { data.file_folder }  else { data.question_group_name }
#let requester_name = if "requester_name" in data { data.requester_name } else { "" }
#let requester_role = if "requester_role" in data { data.requester_role } else { "" }
#let generated_date = if "generated_date" in data { data.generated_date } else { "" }

#set page(
  paper: "a4",
  margin: (top: 2cm, bottom: 1.5cm, left: 2.2cm, right: 2.2cm),
  header: [
    #set text(size: 9pt, fill: rgb("#666"))
    #grid(
      columns: (1fr, auto),
      align: (left + horizon, right + horizon),
      [#institution],
      context [Page #counter(page).display()],
    )
    #line(length: 100%, stroke: 0.5pt + rgb("#0a0808"))
  ],
  footer: [
    #set text(size: 8pt, fill: rgb("#0f0d0d"))
    #align(center)[Generated: #generated_date]
  ],
)

#set text(size: 11pt, fill: rgb("#272626"))
#set heading(numbering: none)

// ===== HEADER SECTION =====
#align(center)[
  #grid(
    columns: (auto, auto),
    gutter: 0.5cm,
    align: (right + horizon, left + horizon),
    image("assets/logo.png", width: 2cm),
    align(left)[
      #text(size: 18pt, weight: "bold", fill: rgb("#1a3a52"))[BCP Quiz System] \
      #text(size: 13pt, fill: rgb("#555"))[#institution]
    ]
  )
]

#line(length: 100%, stroke: 1.5pt + rgb("#1a3a52"))
#v(0.4cm)

// ===== SUBJECT & EXAM INFO =====
#align(center)[
  #text(size: 15pt, weight: "bold", fill: rgb("#302c2c"))[SUBJECT] \
  #text(size: 20pt, weight: "bold", fill: rgb("#1a3a52"))[#data.subject_name]
  #v(0.2cm)
  #text(size: 15pt, weight: "bold", fill: rgb("#666"))[FILE FOLDER] \
  #text(size: 18pt, fill: rgb("#333"))[#file_folder]
  #v(0.2cm)
  #text(size: 13pt, fill: rgb("#353333"))[Total Questions: #data.questions.len()]
]

#v(0.4cm)
#line(length: 100%, stroke: 0.5pt + rgb("#dddddd4f"))
#v(0.4cm)

// ===== REQUESTER INFO (auto-filled) =====
#grid(
  columns: (1fr, 1fr, 1fr),
  gutter: 1cm,
  align: center,
  [
    #text(size: 13pt, weight: "bold", fill: rgb("#666"))[NAME] \
    #text(size: 15pt, fill: rgb("#333"))[#requester_name]
  ],
  [
    #text(size: 13pt, weight: "bold", fill: rgb("#666"))[ROLE] \
    #text(size: 15pt, fill: rgb("#333"))[#requester_role]
  ],
  [
    #text(size: 13pt, weight: "bold", fill: rgb("#666"))[DATE] \
    #text(size: 15pt, fill: rgb("#333"))[#generated_date]
  ],
)

#v(0.4cm)

// ===== ANSWER KEY INDICATOR =====
#if data.show_answers [
  #align(center)[
    #box(
      stroke: 2pt + rgb("#c0392b"),
      fill: rgb("#ffebee"),
      inset: 8pt,
      radius: 4pt,
    )[
      #text(size: 12pt, weight: "bold", fill: rgb("#c0392b"))[ANSWER KEY — FOR INSTRUCTOR USE ONLY]
    ]
  ]
  #v(0.4cm)
]

#align(center)[
  #text(size: 10pt, weight: "bold", fill: rgb("#1a3a52"))[QUESTIONS]
]
#v(0.2cm)
#line(length: 100%, stroke: 0.5pt + rgb("#ddd"))
#v(0.3cm)

// ===== QUESTIONS SECTION (two columns) =====
#let letters = ("A", "B", "C", "D", "E", "F", "G", "H")
#let primary = rgb("#1a3a52")
#let correct = rgb("#27ae60")

#columns(2, gutter: 1cm)[
  #for (i, q) in data.questions.enumerate() [
    #block(breakable: false, above: 0pt, below: 0.5cm)[
      #text(weight: "bold", size: 10.5pt, fill: primary)[
        #(i + 1). #q.question_text
      ]
      #v(0.2cm)
      #for (j, a) in q.answers.enumerate() [
        #let marker = if data.show_answers and a.is_correct {
          text(fill: correct, weight: "bold")[●]
        } else {
          text(fill: rgb("#999"))[○]
        }
        #grid(
          columns: (0.5cm, 0.5cm, 1fr),
          align: (center + horizon, left + horizon, left + horizon),
          marker,
          [#letters.at(j).],
          [
            #text(size: 9.5pt, fill: rgb("#333"))[#a.answer_text]
            #if data.show_answers and a.is_correct [
              #h(0.2cm)#text(size: 7.5pt, style: "italic", fill: correct, weight: "bold")[(correct)]
            ]
          ]
        )
        #v(0.1cm)
      ]
    ]
  ]
]

#pagebreak()

#align(center)[
  #text(size: 14pt, weight: "bold", fill: rgb("#1a3a52"))[Answer Key Summary]
  #v(0.2cm)
  #text(size: 10pt, fill: rgb("#666"))[#data.subject_name — #file_folder]
]

#v(0.5cm)

#align(center)[
  #table(
    columns: 2,
    stroke: 0.5pt + rgb("#321da8"),
    align: center + horizon,
    fill: (col, row) => if row == 0 { rgb("#1a3a52") } else { white },
    [#text(fill: white, weight: "bold")[Question No.]], [#text(fill: white, weight: "bold")[Correct Answer]],
    ..data.questions.enumerate().map(pair => {
      let (i, q) = pair
      let correct_idx = q.answers.enumerate().find(a => a.at(1).is_correct)
      let letter = if correct_idx != none { letters.at(correct_idx.at(0)) } else { "—" }
      ([#(i + 1)], [#letter])
    }).flatten()
  )
]
